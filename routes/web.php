<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');


Route::resource('todos', TodoController::class);
Route::get('todos/status/{id}', [TodoController::class,'todoStatusChange'])->name('todos.status');
Route::get('tasks/status/{id}', [TaskController::class,'todoStatusChange'])->name('tasks.status');
Route::resource('tasks', TaskController::class);



