<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        if ($tags === '{}' || $tags === '[]' || $tags === [] || $tags === (object)[]) {
            return [];
        }
        return array_values(array_filter((array)$tags));
    }

    public function index()
    {
        $notes = DB::table('notes')
            ->where('user_id', Auth::id())
            ->orderBy('position')
            ->get();

        foreach ($notes as &$note) {
            $note->tags = $this->cleanTags($note->tags);
        }

        return response()->json($notes);
    }

    // public function store(Request $request)
    // {
    //     $tags = $this->cleanTags($request->tags);

    //     // Get the maximum position value for the current user's notes
    //     $maxPosition = DB::table('notes')
    //         ->where('user_id', Auth::id())
    //         ->max('position');

    //     $id = DB::table('notes')->insertGetId([
    //         'title' => $request->title,
    //         'content' => $request->content,
    //         'color' => $request->color,
    //         'tags' => json_encode($tags),
    //         'user_id' => Auth::id(),
    //         'position' => ($maxPosition ?? -1) + 1  // Set position to max + 1
    //     ]);

    //     return response()->json([
    //         'id' => $id,
    //         'title' => $request->title,
    //         'content' => $request->content,
    //         'color' => $request->color,
    //         'tags' => $tags,
    //         'user_id' => Auth::id(),
    //         'position' => ($maxPosition ?? -1) + 1
    //     ]);
    // }


    //     public function store(Request $request)
    // {
    //     try {
    //         $validated = $request->validate([
    //             'title' => 'required|string|max:255',
    //             'content' => 'required|string',
    //             'color' => 'required|string',
    //             'tags' => 'array'
    //         ]);

    //         $note = Note::create($validated);

    //         return response()->json($note, 201);
    //     } catch (\Exception $e) {
    //         return response()->json(['message' => $e->getMessage()], 422);
    //     }
    // }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'color' => 'required|string',
                'tags' => 'array'
            ]);

            $validated['user_id'] = Auth::id();

            $note = Note::create($validated);

            return response()->json($note, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function update(Request $request, Note $note)
    {
        $tags = $this->cleanTags($request->tags);

        DB::table('notes')
            ->where('id', $note->id)
            ->where('user_id', Auth::id())
            ->update([
                'title' => $request->title,
                'content' => $request->content,
                'color' => $request->color,
                'tags' => json_encode($tags)
            ]);

        return response()->json([
            'id' => $note->id,
            'title' => $request->title,
            'content' => $request->content,
            'color' => $request->color,
            'tags' => $tags,
            'user_id' => Auth::id()
        ]);
    }

    public function destroy(Note $note)
    {
        $this->authorize('delete', $note);
        $note->delete();
        return response()->json(['message' => 'Note deleted successfully']);
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'noteId' => 'required|exists:notes,id',
            'targetIndex' => 'required|integer|min:0'
        ]);

        $note = Note::findOrFail($request->noteId);
        $targetIndex = $request->targetIndex;

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
}
