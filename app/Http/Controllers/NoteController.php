<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        return $request->user()->notes()->where('note_id', $id)->first();
    }

    function update(Request $request, $id) {
        $validated = $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'tags' => 'json|nullable',
            'folder' => 'string|nullable',
            'is_pinned' => 'boolean'
        ]);



        $request->user()->notes()->where('note_id', $id)->update($validated);

        $note = $request->user()->notes()->where('note_id', $id)->first();

        return [
            'message' => 'Note updated successfully',
            'note' => $note
        ];
    }

    function delete(Request $request, $id) {
        $request->user()->notes()->where('note_id', $id)->delete();

        return [
            'message' => 'Note deleted successfullY'
        ];
    }

}
