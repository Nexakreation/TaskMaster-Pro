<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AdvancedAnalyticsController extends Controller
{
    public function getAdvancedMetrics(Request $request)
    {
        try {
            // Get all tasks for the authenticated user
            $tasks = Task::where('user_id', Auth::id())
                        ->select('id', 'text', 'date', 'completed', 'completed_at', 'created_at')
                        ->get();

            // Format tasks data for Python
            $tasksData = $tasks->map(function($task) {
                return [
                    'id' => $task->id,
                    'text' => $task->text,
                    'date' => $task->date->format('Y-m-d'),
                    'completed' => (bool) $task->completed,
                    'completed_at' => $task->completed_at ? $task->completed_at->format('Y-m-d') : null,
                    'created_at' => $task->created_at->format('Y-m-d')
                ];
            })->values()->toArray();

            $metrics = $this->calculateAdvancedMetrics($tasksData);

            if (!$metrics) {
                throw new Exception('Failed to calculate advanced metrics');
            }

            return response()->json($metrics);

        } catch (Exception $e) {
            Log::error('Advanced analytics error: ' . $e->getMessage());
            
            return response()->json([
                'weekly_stats' => [
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
                    'most_productive_day' => 'N/A',
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
                ]
            ], 500);
        }
    }

    private function calculateAdvancedMetrics($tasks)
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
                Log::error('Advanced metrics script execution failed', [
                    'output' => $output,
                    'return_code' => $returnVar
                ]);
                throw new Exception('Failed to process advanced metrics');
            }

            $metrics = json_decode($output[0], true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Failed to parse advanced metrics JSON', [
                    'error' => json_last_error_msg(),
                    'output' => $output[0]
                ]);
                throw new Exception('Invalid advanced metrics data format');
            }

            return $metrics;
        } catch (Exception $e) {
            Log::error('Advanced metrics calculation failed: ' . $e->getMessage());
            return null;
        }
    }

    public function getProductivityScore()
    {
        try {
            $metrics = $this->getAdvancedMetrics(Request::capture());
            return response()->json([
                'productivity_score' => $metrics['productivity_metrics']['productivity_score'] ?? 0
            ]);
        } catch (Exception $e) {
            return response()->json(['productivity_score' => 0], 500);
        }
    }

    public function getWeeklyPatterns()
    {
        try {
            $metrics = $this->getAdvancedMetrics(Request::capture());
            return response()->json([
                'weekly_patterns' => $metrics['time_analysis'] ?? [
                    'most_productive_day' => 'N/A',
                    'weekly_pattern' => []
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'weekly_patterns' => [
                    'most_productive_day' => 'N/A',
                    'weekly_pattern' => []
                ]
            ], 500);
        }
    }

    public function getTrends()
    {
        try {
            $metrics = $this->getAdvancedMetrics(Request::capture());
            return response()->json([
                'trends' => $metrics['trends'] ?? [
                    'recent_completion_rate' => 0,
                    'improvement_rate' => 0,
                    'tasks_due_today' => 0,
                    'tasks_due_this_week' => 0
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'trends' => [
                    'recent_completion_rate' => 0,
                    'improvement_rate' => 0,
                    'tasks_due_today' => 0,
                    'tasks_due_this_week' => 0
                ]
            ], 500);
        }
    }
} 