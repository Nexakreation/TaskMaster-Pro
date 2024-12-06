<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use Exception;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class TaskAnalyticsController extends Controller
{
    private function getEmptyAnalytics()
    {
        return [
            "basicStats" => [
                "completion_rate" => 0,
                "total_tasks" => 0,
                "completed_tasks" => 0,
                "overdue_tasks" => 0
            ],
            "productivityMetrics" => [
                "productivity_score" => 0,
                "on_time_completion_rate" => 0
            ],
            "timeAnalysis" => [
                "most_productive_day" => "No Data",
                "weekly_pattern" => [
                    "Monday" => 0, "Tuesday" => 0, "Wednesday" => 0,
                    "Thursday" => 0, "Friday" => 0, "Saturday" => 0, "Sunday" => 0
                ]
            ],
            "trends" => [
                "recent_completion_rate" => 0,
                "improvement_rate" => 0
            ],
            "monthly_data" => [
                "year" => date('Y'),
                "month" => date('n'),
                "daily_completion" => array_fill(0, date('t'), 0),
                "status_distribution" => [
                    "completed" => 0,
                    "pending" => 0,
                    "overdue" => 0,
                    "today" => 0
                ],
                "days_in_month" => date('t')
            ]
        ];
    }

    private function calculateBasicStats($tasks)
    {
        $total = $tasks->count();
        $completed = $tasks->where('completed', true)->count();
        $overdue = $tasks->where('completed', false)
                        ->where('date', '<', now()->startOfDay())
                        ->count();

        return [
            "completion_rate" => $total > 0 ? round(($completed / $total) * 100, 1) : 0,
            "total_tasks" => $total,
            "completed_tasks" => $completed,
            "overdue_tasks" => $overdue
        ];
    }

    private function calculateProductivityMetrics($tasks)
    {
        $total = $tasks->count();
        $completed = $tasks->where('completed', true)->count();
        $overdue = $tasks->where('completed', false)
                        ->where('date', '<', now()->startOfDay())
                        ->count();

        $completionRate = $total > 0 ? ($completed / $total) * 100 : 0;
        $productivityScore = min(100, max(0, 
            ($completionRate * 0.6) + ((100 - $overdue * 10) * 0.4)
        ));

        return [
            "productivity_score" => round($productivityScore),
            "on_time_completion_rate" => round($completionRate, 1)
        ];
    }

    private function calculateTimeAnalysis($tasks)
    {
        $dayPattern = [
            "Monday" => 0, "Tuesday" => 0, "Wednesday" => 0,
            "Thursday" => 0, "Friday" => 0, "Saturday" => 0, "Sunday" => 0
        ];

        foreach ($tasks->where('completed', true) as $task) {
            $date = $task->date instanceof Carbon 
                ? $task->date 
                : Carbon::parse($task->date);
            
            $dayName = $date->format('l'); // Get day name
            $dayPattern[$dayName]++;
        }

        $mostProductiveDay = array_search(max($dayPattern), $dayPattern);

        return [
            "most_productive_day" => $mostProductiveDay ?: "No Data",
            "weekly_pattern" => $dayPattern
        ];
    }

    private function calculateMonthlyData($tasks, $year, $month)
    {
        $daysInMonth = Carbon::create($year, $month)->daysInMonth;
        $dailyCompletion = array_fill(0, $daysInMonth, 0);
        
        $today = now()->startOfDay();
        $completed = 0;
        $pending = 0;
        $overdue = 0;
        $todayTasks = 0;

        foreach ($tasks as $task) {
            $day = (int)$task->date->format('d') - 1;
            $dailyCompletion[$day]++;

            if ($task->completed) {
                $completed++;
            } elseif ($task->date->startOfDay()->eq($today)) {
                $todayTasks++;
            } elseif ($task->date->startOfDay()->lt($today)) {
                $overdue++;
            } else {
                $pending++;
            }
        }

        return [
            'monthly_data' => [
                'days_in_month' => $daysInMonth,
                'daily_completion' => array_values($dailyCompletion),
                'status_distribution' => [
                    'completed' => $completed,
                    'pending' => $pending,
                    'overdue' => $overdue,
                    'today' => $todayTasks
                ],
                'total_tasks' => $tasks->count()
            ]
        ];
    }

    private function getTimeAnalysis()
    {
        $tasks = Task::where('user_id', Auth::id())->get();
        
        if ($tasks->isEmpty()) {
            return [
                'most_productive_day' => 'No data',
                'weekly_pattern' => [
                    'Monday' => 0, 'Tuesday' => 0, 'Wednesday' => 0,
                    'Thursday' => 0, 'Friday' => 0, 'Saturday' => 0, 'Sunday' => 0
                ]
            ];
        }

        return $this->calculateTimeAnalysis($tasks);
    }

    private function getBasicStats()
    {
        $tasks = Task::where('user_id', Auth::id())->get();
        return $this->calculateBasicStats($tasks);
    }

    private function getProductivityMetrics()
    {
        $tasks = Task::where('user_id', Auth::id())->get();
        return $this->calculateProductivityMetrics($tasks);
    }

    private function getTrends()
    {
        $tasks = Task::where('user_id', Auth::id())
            ->where('created_at', '>=', now()->subDays(30))
            ->get();

        $recentCompletionRate = $tasks->isNotEmpty() 
            ? round(($tasks->where('completed', true)->count() / $tasks->count()) * 100, 1)
            : 0;

        // Calculate improvement rate (simplified version)
        $improvementRate = 0; // You can implement a more sophisticated calculation

        return [
            'recent_completion_rate' => $recentCompletionRate,
            'improvement_rate' => $improvementRate
        ];
    }

    private function runPythonAnalysis($tasks)
    {
        try {
            Log::info('Starting Python analysis');
            
            // Format tasks data for Python
            $tasksData = $tasks->map(function ($task) {
                return [
                    'date' => $task->date->format('Y-m-d'),
                    'completed' => (bool)$task->completed,
                    'completed_at' => $task->completed_at ? $task->completed_at->format('Y-m-d H:i:s') : null,
                    'created_at' => $task->created_at->format('Y-m-d H:i:s'),
                    'status' => $task->status ?? 'pending'
                ];
            })->toArray();

            Log::info('Tasks data prepared:', [
                'count' => count($tasksData),
                'sample' => array_slice($tasksData, 0, 2),
                'has_completed' => collect($tasksData)->where('completed', true)->count()
            ]);

            $tempFile = tempnam(sys_get_temp_dir(), 'tasks_');
            file_put_contents($tempFile, json_encode($tasksData, JSON_PRETTY_PRINT));
            
            // Use 'python' instead of 'python3' on Windows
            $pythonCommand = PHP_OS_FAMILY === 'Windows' ? 'python' : 'python3';
            
            $process = new Process([
                $pythonCommand,
                base_path('analytics/task_analyzer.py'),
                $tempFile
            ]);

            $process->setTimeout(60);
            $process->run();
            
            Log::info('Python process output:', [
                'output' => $process->getOutput(),
                'error' => $process->getErrorOutput()
            ]);

            unlink($tempFile);

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            $result = json_decode($process->getOutput(), true);
            
            if (!$result) {
                throw new Exception('Failed to decode Python output');
            }

            Log::info('Python analysis result:', $result);
            
            return $result;

        } catch (Exception $e) {
            Log::error('Python analysis failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return $this->getEmptyAnalytics();
        }
    }

    public function index()
    {
        try {
            Log::info('Fetching analytics data');
            
            $tasks = Task::where('user_id', Auth::id())
                         ->whereNotNull('date')  // Ensure we have dates
                         ->get();
            
            Log::info('Found tasks:', ['count' => $tasks->count()]);
            
            // Get Python analysis
            $pythonAnalysis = $this->runPythonAnalysis($tasks);
            
            // Use Python results with fallbacks
            $timeAnalysis = $pythonAnalysis['time_analysis'] ?? $this->getEmptyAnalytics()['timeAnalysis'];
            $productivityMetrics = $pythonAnalysis['productivity_metrics'] ?? $this->getEmptyAnalytics()['productivityMetrics'];
            $trends = $pythonAnalysis['trends'] ?? $this->getEmptyAnalytics()['trends'];
            
            // Calculate basic stats in PHP
            $basicStats = $this->calculateBasicStats($tasks);
            
            // Get monthly data for charts
            $viewData = $this->calculateMonthlyData(
                Task::where('user_id', Auth::id())
                    ->whereYear('date', date('Y'))
                    ->whereMonth('date', date('n'))
                    ->get(),
                date('Y'),
                date('n')
            );
            
            $emptyData = $this->getEmptyAnalytics();
            
            // Add additional metrics to view data
            $viewData['completion_streak'] = $productivityMetrics['completion_streak'] ?? 0;
            $viewData['efficiency_score'] = $productivityMetrics['efficiency_score'] ?? 0;
            
            Log::info('Final data for view:', [
                'timeAnalysis' => $timeAnalysis,
                'productivityMetrics' => $productivityMetrics,
                'trends' => $trends
            ]);

            return view('analytics.index', compact(
                'timeAnalysis',
                'basicStats',
                'productivityMetrics',
                'trends',
                'viewData',
                'emptyData'
            ));
        } catch (Exception $e) {
            Log::error('Error in analytics index: ' . $e->getMessage());
            return view('analytics.index')->with('error', 'Unable to load analytics data');
        }
    }

    // API endpoint for monthly data
    public function getMonthlyData(Request $request)
    {
        try {
            $year = $request->input('year', date('Y'));
            $month = $request->input('month', date('n'));
            
            $tasks = Task::where('user_id', Auth::id())
                        ->whereYear('date', $year)
                        ->whereMonth('date', $month)
                        ->select('id', 'text', 'date', 'completed', 'completed_at', 'created_at')
                        ->orderBy('date')
                        ->get();

            return response()->json(
                $this->calculateMonthlyData($tasks, $year, $month)
            );
            
        } catch (Exception $e) {
            Log::error('Monthly analytics error: ' . $e->getMessage());
            return response()->json([
                'monthly_data' => [
                    'error' => $e->getMessage(),
                    'daily_completion' => array_fill(0, date('t', strtotime("$year-$month-01")), 0),
                    'status_distribution' => [
                        'completed' => 0,
                        'pending' => 0,
                        'overdue' => 0,
                        'today' => 0
                    ],
                    'days_in_month' => date('t', strtotime("$year-$month-01")),
                    'total_tasks' => 0
                ]
            ], 500);
        }
    }

    public function getPythonAnalysis()
    {
        try {
            $tasks = Task::where('user_id', Auth::id())->get();
            $pythonAnalysis = $this->runPythonAnalysis($tasks);
            
            if (!isset($pythonAnalysis['time_analysis']) || !isset($pythonAnalysis['productivity_metrics'])) {
                throw new Exception('Invalid Python analysis result');
            }
            
            return response()->json($pythonAnalysis);
        } catch (Exception $e) {
            Log::error('Python analysis endpoint error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to get analytics data',
                'time_analysis' => $this->getEmptyAnalytics()['timeAnalysis'],
                'productivity_metrics' => $this->getEmptyAnalytics()['productivityMetrics']
            ], 500);
        }
    }
} 