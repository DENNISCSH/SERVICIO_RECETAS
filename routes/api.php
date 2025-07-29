<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RecipeController;

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout']);
Route::get('/me', [UserController::class, 'me'])->middleware('auth');

Route::post('/recipes_register', [RecipeController::class, 'store']);
Route::get('/recipes_list', [RecipeController::class, 'index']);
Route::get('/recipes/{id}', [RecipeController::class, 'show']);
Route::put('/recipes_update/{id}', [RecipeController::class, 'update']);
Route::delete('/recipes_destroy/{id}', [RecipeController::class, 'destroy']);

