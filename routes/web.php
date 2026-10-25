<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

// Redirect root to task listing
Route::get('/', [TaskController::class, 'index'])->name('home');

// Resourceful Task Routes (CRUD)
Route::resource('tasks', TaskController::class);

// Quick status change route
Route::patch('tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.status');
