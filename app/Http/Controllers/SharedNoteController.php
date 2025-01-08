<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SharedNote;
use Illuminate\Validation\Rule;
use App\Models\Note;

class SharedNoteController extends Controller
{
    public function index(Request $request) {
        return [
            'shared_notes' => $request->user()->shared_with
        ];
    }

    public function store(Request $request, $id) {
        $validated = $request->validate([
            'shared_with' => 'required|email|exists:users,email',
            'permission' => ['required', 'string', Rule::in('view', 'edit')]
        ]);


        $note = $request->user()->notes()->where('note_id', $id)->first(); // find the note to be shared from user's inventory

        $shared_with = User::where('email', $validated['shared_with'])->first(); // find the user to share the note with

        if (!$note) {
            return response()->json(['message' => 'Note not found'], 404);
        }

        if ($shared_with['id'] == $request->user()->id) {
            return response()->json(['message' => 'Cannot share with note owner!'], 422);
        }

        //return ['hello' => $shared_with];

        $shared_note = SharedNote::create([
            'shared_by_user_id' => $request->user()->id,
            'shared_with_id' => $shared_with['id'],
            'note_id' => $note['note_id'],
            'permission' => $validated['permission'],
            'shared_at' => now()
        ]);

        return [
            'message' => 'Note shared successfully',
            'shared_note' => $shared_note
        ];
    }

    public function delete(Request $request, $id, $share_id) {
        $note = Note::find($id);

        $shared = SharedNote::find($share_id);

        if (!$shared) {
            return response()->json(['message'=>'Note not found'], 404);
        }

        if ($shared['shared_by_user_id'] != $request->user()->id) {
            return response()->json(['message' => 'User not permitted to revoke note sharing'], 403);
        }

        $shared->delete();

        $note = Note::find($id);

        return [
            'message' => 'Revoked successfully',
            'shared_with' => $note->shared_with()->get()
        ];
    }
}
