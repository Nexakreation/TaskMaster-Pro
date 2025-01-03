<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = DB::table('tasks')
            ->where('user_id', Auth::id())
            ->orderBy('date')
            ->get();
        return response()->json($tasks);
    }

    public function store(Request $request)
    {
        $id = DB::table('tasks')->insertGetId([
            'text' => $request->text,
            'date' => $request->date,
            'completed' => false,
            'user_id' => Auth::id()
        ]);

        return response()->json([
            'id' => $id,
            'text' => $request->text,
            'date' => $request->date,
            'completed' => false,
            'user_id' => Auth::id()
        ]);
    }

    public function update(Request $request, $id)
    {
        DB::table('tasks')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->update([
                'text' => $request->text,
                'date' => $request->date,
                'completed' => $request->completed ? 1 : 0
            ]);

        return response()->json(['message' => 'Task updated successfully']);
    }

    public function destroy($id)
    {
        DB::table('tasks')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->delete();

        return response()->json(['message' => 'Task deleted successfully']);
    }
}
