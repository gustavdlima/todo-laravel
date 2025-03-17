<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    // Create task
    Route::post('/tasks', [TaskController::class, 'store']);

    // Update task
    Route::put('/tasks/{taskId}', [TaskController::class, 'update']);

    // Filter tasks by creation date
    Route::get('/tasks/creation-date/{userId}', [TaskController::class, 'filterByCreationDate']);

    // Filtrar tasks by status
    Route::get('/tasks/status/{userId}', [TaskController::class, 'filterByStatus']);

    // Add comment
    Route::post('/tasks/{taskId}/comments', [TaskController::class, 'addComment']);

    // Delete Task
    Route::delete('/tasks/{taskId}', [TaskController::class, 'delete']);
});
