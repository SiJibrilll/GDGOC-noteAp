<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\SharedNoteController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Route::get('/hello', function (Request $request) {
//     return $request;
// });

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

Route::get('/', function() {
    return response()->json([
        'Hello' => 'world'
    ]);
});

Route::middleware('api')->group(function () {
    Route::post('/register', [UserController::class, 'register']);

    Route::post('/login', [UserController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [UserController::class, 'logout']);

        Route::get('/notes', [NoteController::class, 'index']);

        Route::post('/notes', [NoteController::class, 'store']);

        Route::get('/notes/shared', [SharedNoteController::Class, 'index']);

        Route::get('/notes/{id}', [NoteController::class, 'show']);

        Route::put('/notes/{id}', [NoteController::class, 'update']);

        Route::delete('/notes/{id}', [NoteController::class, 'delete']);

        Route::post('/notes/{id}/share', [SharedNoteController::class, 'store']);

        Route::delete('/notes/{id}/share/{share_id}', [SharedNoteController::class, 'delete']);
    });
});
