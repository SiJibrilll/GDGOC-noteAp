<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SharedNote;

class SharedNoteController extends Controller
{
    public function index(Request $request) {
        return [
            'shared_notes' => $request->user()->shared_with
        ];
    }

    public function store(Request $request, $id) {
        $validated = $request->validate([
            'shared_with' => 'required|email|exists:users,email'
        ]);

        $note = $request->user()->notes()->where('note_id', $id)->first();

        $shared_with = User::where('email', $validated['shared_with'])->first();

        if (!$note) {
            return response()->json(['message' => 'Note not found'], 404);
        }

        if ($shared_with['id'] == $request->user()->id) {
            return response()->json(['message' => 'Cannot share with note owner!'], 422);
        }

        //return ['hello' => $shared_with];

        $shared_note = $note->shared_with()->create([
            'shared_with_id' => $shared_with['id'],
            'shared_by_user_id' => $request->user()->id,
            'shared_at' => now()
        ]);

        return [
            'message' => 'Note shared successfully',
            'shared_note' => $shared_note
        ];
    }

    public function delete(Request $request, $id, $share_id) {
        $shared = SharedNote::find($share_id);

        if (!$shared) {
            return response()->json(['message'=>'Note not found'], 404);
        }

        if ($shared['shared_by_user_id'] != $request->user()->id) {
            return response()->json(['message' => 'User not permitted to revoke note sharing'], 403);
        }

        $shared->delete();

        return ['message' => 'Revoked successfully'];
    }
}
