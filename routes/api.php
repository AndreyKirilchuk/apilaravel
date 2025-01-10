<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth')->group(function (){
    Route::post('/tasks', [\App\Http\Controllers\TaskController::class, 'create']);
    Route::get('/projects/{project_id}/tasks', [\App\Http\Controllers\TaskController::class, 'index']);
    Route::patch('/tasks/{task_id}/status', [\App\Http\Controllers\TaskController::class, 'update']);
});

Route::post('/auth/register', [\App\Http\Controllers\UserController::class, 'register']);
Route::post('/auth/login', [\App\Http\Controllers\UserController::class, 'login']);
