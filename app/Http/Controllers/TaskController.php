<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDO;
use PDOException;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    private function getConnection() {
        $pdo = new PDO("mysql:host=localhost;dbname=todo_app", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }

    public function index()
    {
        try {
            $pdo = $this->getConnection();
            $stmt = $pdo->prepare("SELECT * FROM tasks WHERE user_id = ? ORDER BY date");
            $stmt->execute([Auth::id()]);
            $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return response()->json($tasks);
        } catch (PDOException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $pdo = $this->getConnection();
            $stmt = $pdo->prepare("INSERT INTO tasks (text, date, completed, user_id) VALUES (?, ?, ?, ?)");
            $stmt->execute([
                $request->text,
                $request->date,
                false,
                Auth::id()
            ]);

            return response()->json([
                'id' => $pdo->lastInsertId(),
                'text' => $request->text,
                'date' => $request->date,
                'completed' => false,
                'user_id' => Auth::id()
            ]);
        } catch (PDOException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $pdo = $this->getConnection();
            $stmt = $pdo->prepare("UPDATE tasks SET text = ?, date = ?, completed = ? WHERE id = ? AND user_id = ?");
            $stmt->execute([
                $request->text,
                $request->date,
                $request->completed ? 1 : 0,
                $id,
                Auth::id()
            ]);

            return response()->json(['message' => 'Task updated successfully']);
        } catch (PDOException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $pdo = $this->getConnection();
            $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
            $stmt->execute([$id, Auth::id()]);

            return response()->json(['message' => 'Task deleted successfully']);
        } catch (PDOException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
} 