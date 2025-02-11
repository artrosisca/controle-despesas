<?php

use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->group(function () {
    // Rotas de autenticação
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

    // Rotas para CRUD de usuários
    Route::get('/users', [UserController::class, 'index'])->middleware('auth:sanctum');
    Route::post('/users', [UserController::class, 'store'])->middleware('auth:sanctum');
    Route::get('/users/{id}', [UserController::class, 'show'])->middleware('auth:sanctum');
    Route::put('/users/{id}', [UserController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->middleware('auth:sanctum');

    // Rotas para adicionar e remover amigos
    Route::post('/users/{id}/add-friend', [UserController::class, 'addFriend'])->middleware('auth:sanctum');
    Route::post('/users/{id}/remove-friend', [UserController::class, 'removeFriend'])->middleware('auth:sanctum');

    // Rotas para CRUD de despesas
    Route::get('/expenses', [ExpenseController::class, 'index'])->middleware('auth:sanctum');
    Route::post('/expenses', [ExpenseController::class, 'store'])->middleware('auth:sanctum');
    Route::get('/expenses/{id}', [ExpenseController::class, 'show'])->middleware('auth:sanctum');
    Route::put('/expenses/{id}', [ExpenseController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/expenses/{id}', [ExpenseController::class, 'destroy'])->middleware('auth:sanctum');
});