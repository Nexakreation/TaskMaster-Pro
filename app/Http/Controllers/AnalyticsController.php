<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    private function getEmptyAnalytics()
    {
        return [
            'weeklyStats' => [
                'completion_rate' => 0,
                'total_tasks' => 0,
                'completed_tasks' => 0,
                'overdue_tasks' => 0
            ],
            'productivity_metrics' => [
                'productivity_score' => 0,
                'on_time_completion_rate' => 0
            ],
            'time_analysis' => [
                'most_productive_day' => 'No Data',
                'weekly_pattern' => [
                    'Monday' => 0, 'Tuesday' => 0, 'Wednesday' => 0,
                    'Thursday' => 0, 'Friday' => 0, 'Saturday' => 0, 'Sunday' => 0
                ]
            ],
            'trends' => [
                'recent_completion_rate' => 0,
                'improvement_rate' => 0,
                'tasks_due_today' => 0,
                'tasks_due_this_week' => 0
            ],
            'monthly_data' => [
                'days_in_month' => date('t'),
                'daily_completion' => array_fill(0, date('t'), 0),
                'status_distribution' => [
                    'completed' => 0,
                    'pending' => 0,
                    'overdue' => 0,
                    'today' => 0
                ],
                'total_tasks' => 0
            ]
        ];
    }

    public function index()
    {
        try {
            // Get authenticated user's tasks
            $tasks = Task::where('user_id', Auth::id())
                        ->select('id', 'text', 'date', 'completed', 'created_at')
                        ->orderBy('date')
                        ->get();
            
            if ($tasks->isEmpty()) {
                $emptyData = $this->getEmptyAnalytics();
                return view('analytics.index')->with([
                    'viewData' => $emptyData,
                    'completionRate' => 0
                ]);
            }

            // Format tasks for Python processing
            $formattedTasks = $tasks->map(function($task) {
                return [
                    'id' => $task->id,
                    'text' => $task->text,
                    'date' => $task->date->format('Y-m-d'),
                    'completed' => (bool) $task->completed,
                    'created_at' => $task->created_at->format('Y-m-d')
                ];
            })->values()->toArray();

            // Process analytics using Python
            $analytics = $this->processAnalytics($formattedTasks);
            
            if (!$analytics) {
                throw new Exception('Failed to process analytics data');
            }

            // Calculate completion rate
            $completed = $analytics['monthly_data']['status_distribution']['completed'] ?? 0;
            $total = $analytics['monthly_data']['total_tasks'] ?? 0;
            $completionRate = $total > 0 ? round(($completed / $total) * 100) : 0;

            return view('analytics.index')->with([
                'viewData' => $analytics,
                'completionRate' => $completionRate
            ]);

        } catch (Exception $e) {
            Log::error('Analytics processing failed: ' . $e->getMessage());
            $emptyData = $this->getEmptyAnalytics();
            return view('analytics.index')->with([
                'viewData' => $emptyData,
                'completionRate' => 0,
                'error' => 'Unable to process analytics at this time.'
            ]);
        }
    }

    private function getAdvancedMetrics($tasks)
    {
        try {
            $pythonPath = 'python';
            $pythonScript = base_path('analytics/advanced_metrics_analyzer.py');
            
            if (!file_exists($pythonScript)) {
                throw new Exception('Advanced metrics analyzer script not found');
            }

            // Prepare the command with proper JSON encoding
            $tasksJson = json_encode($tasks);
            $tasksJson = str_replace('"', '\"', $tasksJson);
            
            $command = "{$pythonPath} \"{$pythonScript}\" \"{$tasksJson}\"";
            
            $output = [];
            $returnVar = null;
            
            exec($command . " 2>&1", $output, $returnVar);
            
            if ($returnVar !== 0 || empty($output)) {
                throw new Exception('Failed to process advanced metrics');
            }

            $metrics = json_decode($output[0], true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Invalid advanced metrics data format');
            }

            return $metrics;
        } catch (Exception $e) {
            Log::error('Advanced metrics processing failed: ' . $e->getMessage());
            return null;
        }
    }

    public function getMonthlyData(Request $request)
    {
        try {
            // Get authenticated user's tasks for the specified month
            $year = $request->input('year', date('Y'));
            $month = $request->input('month', date('m'));
            
            $tasks = Task::where('user_id', Auth::id())
                ->whereYear('date', $year)
                ->whereMonth('date', $month)
                ->select('id', 'text', 'date', 'completed', 'created_at')
                ->orderBy('date')
                ->get();

            // Format tasks for Python processing
            $formattedTasks = $tasks->map(function($task) {
                return [
                    'id' => $task->id,
                    'text' => $task->text,
                    'date' => $task->date->format('Y-m-d'),
                    'completed' => (bool) $task->completed,
                    'created_at' => $task->created_at->format('Y-m-d')
                ];
            })->values()->toArray();

            // If no tasks, return empty analytics
            if (empty($formattedTasks)) {
                return response()->json($this->getEmptyAnalytics());
            }

            // Process analytics using Python
            $analytics = $this->processAnalytics($formattedTasks);
            
            if (!$analytics) {
                throw new Exception('Failed to process analytics data');
            }

            return response()->json($analytics);

        } catch (\Exception $e) {
            Log::error('Analytics processing failed: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to process analytics',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function completionRate()
    {
        $days = 7; // Last 7 days
        $data = [];
        
        for ($i = 0; $i < $days; $i++) {
            $date = now()->subDays($i)->format('Y-m-d');
            
            $total = Task::whereDate('date', $date)->count();
            $completed = Task::whereDate('date', $date)
                ->where('completed', true)
                ->count();
                
            $data[] = [
                'date' => now()->subDays($i)->format('M d'),
                'total_count' => $total,
                'completed_count' => $completed
            ];
        }
        
        return response()->json(array_reverse($data));
    }

    private function getWeeklyStats()
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $tasks = Task::where('user_id', Auth::id())
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->get();

        $completedTasks = $tasks->where('completed', true)->count();
        $totalTasks = $tasks->count();
        $overdueTasks = $tasks->where('completed', false)
            ->where('date', '<', Carbon::now())
            ->count();

        return [
            'completion_rate' => $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0,
            'total_tasks' => $totalTasks,
            'completed_tasks' => $completedTasks,
            'overdue_tasks' => $overdueTasks,
        ];
    }

    private function processAnalytics($tasks)
    {
        try {
            // Convert tasks to JSON
            $tasksJson = json_encode($tasks);
            
            // Escape quotes for command line
            $tasksJson = str_replace('"', '\"', $tasksJson);
            
            // Execute Python script
            $command = "python " . base_path('analytics/task_analyzer.py') . " \"{$tasksJson}\"";
            exec($command, $output, $returnVar);
            
            if ($returnVar !== 0) {
                throw new Exception('Python script execution failed');
            }
            
            // Parse the output
            return json_decode($output[0], true);
        } catch (Exception $e) {
            Log::error('Analytics processing failed: ' . $e->getMessage());
            return null;
        }
    }
} 