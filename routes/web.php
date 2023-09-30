<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TodoController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::match(['get','post'],'/',[ AuthController::class,'login'])->name('login');
Route::match(['get','post'],'/sign-up',[ AuthController::class,'signUp'])->name('signUp');

Route::middleware(['auth'])->group(function(){
    Route::post('/logout',[ AuthController::class,'logout'])->name('logout');
    Route::get('/home',[ TodoController::class,'home'])->name('home');
    Route::post('/add-todo',[ TodoController::class,'addTodo'])->name('addTodo');
    Route::get('user/{user}/todo/{todo}',[ TodoController::class,'editTodo'])->name('editTodo')->scopeBindings();
    Route::post('/update-todo/{todo}',[ TodoController::class,'updateTodo'])->name('updateTodo');
    Route::delete('user/{user}/todo/{todo}',[ TodoController::class,'deleteTodo'])->name('deleteTodo')->scopeBindings();
    Route::get('/complete-todo/{todo}',[ TodoController::class,'completeTodo'])->name('completeTodo');
});


