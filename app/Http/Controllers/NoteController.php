<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;

class NoteController extends Controller
{
    function index(Request $request) {
        return [
            'notes' => $request->user()->notes
        ];
    }

    function store(Request $request) {
        $validated = $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'tags' => 'json|nullable',
            'folder' => 'string|nullable',
            'is_pinned' => 'boolean'
        ]);



        $note = $request->user()->notes()->create($validated);

        return [
            'message' => 'Note created successfully',
            'note' => $note
        ];
    }

    function show(Request $request, $id) {
        $note = $request->user()->notes()->where('note_id', $id)->first();


        if ($note) {
            return [
                'note' => $note,
                'shared_with' => $note->shared_with()->get()
            ];
        }

        $shared = $request->user()->shared_with()->wherePivot('note_id', $id)->first();

        if ($shared) {
            return $shared;
        }

        return response()->json(['message' => 'Note not found'], 404);
    }

    function update(Request $request, $id) {
        $validated = $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'tags' => 'json|nullable',
            'folder' => 'string|nullable',
            'is_pinned' => 'boolean'
        ]);

        $note = Note::where('note_id', $id)->first();

        if (!$note) {
            return ['message' => 'Note not found'];
        }


        $shared = $note->shared_with()->where('shared_with_id', $request->user()->id)->first();

        if (!$shared) {
            return response()->json(['message'=>'You do not have permission to edit this note'], 422);
        }

        if($shared->pivot->permission != 'edit' && $note['user_id'] != $request->user()->id) {
            return response()->json(['message'=>'You do not have the permission to edit this note'], 422);
        }


        $note->update($validated);

        return [
            'message' => 'Note updated successfully',
            'note' => $note
        ];
    }

    function delete(Request $request, $id) {
        $note = $request->user()->notes()->where('note_id', $id)->first();

        if ($note) {
            $note->delete();

             return [
                'message' => 'Note deleted successfully'
             ];

        } else {
            return [
                'message' => 'Note not found'
            ];
        }
    }

}
