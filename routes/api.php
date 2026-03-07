<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TodoController;

Route::prefix('v1')->group(function () {

    Route::get('/todos', [TodoController::class, 'index']);
    Route::post('/todos', [TodoController::class, 'store']);
    Route::get('/todos/{id}', [TodoController::class, 'show']);
    Route::put('/todos/{id}', [TodoController::class, 'update']);
    Route::delete('/todos/{id}', [TodoController::class, 'destroy']);
});
