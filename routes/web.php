<?php

use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [TodoController::class, 'index'])->name('tasks.index');
Route::post('/', [TodoController::class, 'store'])->name('tasks.store');
Route::put('/{task}', [TodoController::class, 'update'])->name('tasks.update');
Route::delete('/{task}', [TodoController::class, 'delete'])->name('tasks.delete');
Route::delete('/', [TodoController::class, 'deleteAll'])->name('tasks.deleteAll');
Route::patch('/{task}', [TodoController::class, 'updateStatus'])->name('tasks.updateStatus');

// Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
