<?php

namespace App\Services;

use App\Models\Task;

class TaskAnalyticsService
{
    public function getTasks()
    {
        return Task::all()->map(function ($task) {
            return [
                'id' => $task->id,
                'text' => $task->text,
                'date' => $task->date,
                'completed' => $task->completed
            ];
        });
    }

    public function getTaskAnalytics()
    {
        $output = [];
        $pythonScript = base_path('analytics/task_analyzer.py');
        $tasksJson = escapeshellarg(json_encode($this->getTasks()));
        
        exec("python3 {$pythonScript} {$tasksJson}", $output);
        
        return json_decode($output[0], true);
    }
} 