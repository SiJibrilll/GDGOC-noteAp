<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Route::get('/hello', function (Request $request) {
//     return $request;
// });

Route::get('/', function() {
    return response()->json([
        'Hello' => 'world'
    ]);
});

Route::middleware('api')->group(function () {
    Route::post('/register', [UserController::class, 'register']);

    Route::post('/login', [UserController::class, 'login']);
});
