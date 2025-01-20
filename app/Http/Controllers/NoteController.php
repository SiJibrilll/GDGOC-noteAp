<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

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
            'is_pinned' => 'boolean',
            'files' => 'file|nullable',
            'files.*' => 'file|max:2048'
        ]);



        $note = $request->user()->notes()->create($validated);

        $note = $note->fresh();

        if ($request->hasFile('files')) {
            $filename = Str::random(32) . "." . $request->file('files')->getClientOriginalExtension();
            $path = $request->file('files')->storeAs('', $filename, 'public');
            $note->files()->create(["path" => 'storage/' . $path]);
        }

        $files = $note->files()->get();

        for ($i = 0; $i < count($files); $i++) {
            $files[$i]['path'] = asset($files[$i]['path']);
        }

        return [
            'message' => 'Note created successfully',
            'note' => $note->toArray(),
            'files' => $files
        ];
    }

    function show(Request $request, $id) {
        $note = $request->user()->notes()->where('note_id', $id)->first();


        if ($note) {
            $files = $note->files()->get();

            for ($i = 0; $i < count($files); $i++) {
                $files[$i]['path'] = asset($files[$i]['path']);
            }


            return [
                'note' => $note,
                'shared_with' => $note->shared_with()->get(),
                'files' => $files
            ];
        }

        $shared = $request->user()->shared_with()->wherePivot('note_id', $id)->first();

        if ($shared) {
            $files = $shared->files()->get();

            for ($i = 0; $i < count($files); $i++) {
                $files[$i]['path'] = asset($files[$i]['path']);
            }

            return [
                'note' => $shared,
                'files' => $files
            ];
        }


        return response()->json(['message' => 'Note not found'], 404);
    }

    function update(Request $request, $id) {
        $validated = $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'tags' => 'json|nullable',
            'folder' => 'string|nullable',
            'is_pinned' => 'boolean',
            'files' => 'file|nullable',
            'files.*' => 'file|max:2048'

        ]);

        $note = Note::where('note_id', $id)->first();

        if (!$note) {
            return ['message' => 'Note not found'];
        }

        if ($note['user_id'] == $request->user()->id) {
            $note->update($validated);
            $note->files()->delete();

            $note = $note->fresh();

            if ($request->hasFile('files')) {
                $filename = Str::random(32) . "." . $request->file('files')->getClientOriginalExtension();
                $path = $request->file('files')->storeAs('', $filename, 'public');
                $note->files()->create(["path" => 'storage/' . $path]);
            }

            $files = $note->files()->get();

            for ($i = 0; $i < count($files); $i++) {
                $files[$i]['path'] = asset($files[$i]['path']);
            }


            return [
                'message' => 'Note updated successfully',
                'note' => $note,
                'files' => $files
            ];
        }

        // if the user does not own the note (for sharing)

        $shared = $note->shared_with()->where('shared_with_id', $request->user()->id)->first();

        if (!$shared) {
            return response()->json(['message'=>'You do not have permission to edit this note'], 422);
        }

        if($shared->pivot->permission != 'edit') {
            return response()->json(['message'=>'You do not have the permission to edit this note'], 422);
        }


        $note->update($validated);
        $note->files()->delete();

        $note = $note->fresh();

        if ($request->hasFile('files')) {
            $filename = Str::random(32) . "." . $request->file('files')->getClientOriginalExtension();
            $path = $request->file('files')->storeAs('', $filename, 'public');
            $note->files()->create(["path" => 'storage/' . $path]);
        }

        $files = $note->files()->get();

        for ($i = 0; $i < count($files); $i++) {
            $files[$i]['path'] = asset($files[$i]['path']);
        }


        return [
            'message' => 'Note updated successfully',
            'note' => $note,
            'files' => $files
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
