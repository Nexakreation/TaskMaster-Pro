<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDO;
use PDOException;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class NoteController extends Controller
{
    use AuthorizesRequests;

    private function cleanTags($tags)
    {
        if (empty($tags)) return [];
        if (is_string($tags)) {
            $tags = json_decode($tags, true);
        }
        // Remove empty objects/arrays
        if ($tags === '{}' || $tags === '[]' || $tags === [] || $tags === (object)[]) {
            return [];
        }
        return array_values(array_filter((array)$tags));
    }

    public function index()
    {
        try {
            $pdo = new \PDO("mysql:host=localhost;dbname=todo_app", "root", "");
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare("SELECT * FROM notes WHERE user_id = ? ORDER BY position");
            $stmt->execute([Auth::id()]);
            $notes = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            // Clean tags for each note
            foreach ($notes as &$note) {
                $note['tags'] = $this->cleanTags($note['tags']);
            }

            return response()->json($notes);
        } catch (PDOException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $pdo = new \PDO("mysql:host=localhost;dbname=todo_app", "root", "");
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            $tags = $this->cleanTags($request->tags);

            $stmt = $pdo->prepare("INSERT INTO notes (title, content, color, tags, user_id) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $request->title,
                $request->content,
                $request->color,
                json_encode($tags),
                Auth::id()
            ]);

            return response()->json([
                'id' => $pdo->lastInsertId(),
                'title' => $request->title,
                'content' => $request->content,
                'color' => $request->color,
                'tags' => $tags,
                'user_id' => Auth::id()
            ]);
        } catch (PDOException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'noteId' => 'required|exists:notes,id',
            'targetIndex' => 'required|integer|min:0'
        ]);

        $note = Note::findOrFail($request->noteId);
        $targetIndex = $request->targetIndex;

        // Update orders
        if ($targetIndex > $note->order) {
            Note::where('order', '>', $note->order)
                ->where('order', '<=', $targetIndex)
                ->decrement('order');
        } else {
            Note::where('order', '>=', $targetIndex)
                ->where('order', '<', $note->order)
                ->increment('order');
        }

        $note->update(['order' => $targetIndex]);

        return response()->json(['message' => 'Note reordered successfully']);
    }

    public function destroy(Note $note)
    {
        $this->authorize('delete', $note);
        $note->delete();
        return response()->json(['message' => 'Note deleted successfully']);
    }

    public function update(Request $request, Note $note)
    {
        try {
            $pdo = new \PDO("mysql:host=localhost;dbname=todo_app", "root", "");
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            $tags = $this->cleanTags($request->tags);

            $stmt = $pdo->prepare("UPDATE notes SET title = ?, content = ?, color = ?, tags = ? WHERE id = ? AND user_id = ?");
            $stmt->execute([
                $request->title,
                $request->content,
                $request->color,
                json_encode($tags),
                $note->id,
                Auth::id()
            ]);

            return response()->json([
                'id' => $note->id,
                'title' => $request->title,
                'content' => $request->content,
                'color' => $request->color,
                'tags' => $tags,
                'user_id' => Auth::id()
            ]);
        } catch (PDOException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
} 