<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\UserController;
use App\Models\Todo;
use App\Models\User;

Route::get('/', function () {
    return view('welcome');
});

// resource
Route::resource('todo', TodoController::class)->except(['show']);

Route::get('dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('todo', TodoController::class)->except(['show']);
    Route::delete('todo/', [TodoController::class, 'destroyCompleted'])->name('todo.deleteallcompleted');
    Route::patch('/todo/{todo}/complete', [TodoController::class, 'complete'])->name('todo.complete');
    Route::patch('/todo/{todo}/uncomplete', [TodoController::class, 'uncomplete']);
});

// route middleware 
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('user', UserController::class)->except(['show']);
    Route::patch('/user/{user}/makeadmin', [UserController::class, 'makeadmin'])->name('user.makeadmin');
    Route::patch('/user/{user}/removeadmin', [UserController::class, 'removeadmin'])->name('user.removeadmin');
});

Route::get('/todo', [TodoController::class, 'index'])->name('todo.index');
Route::get('/todo/create', [TodoController::class, 'create'])->name('todo.create');
// Route edit
Route::get('/todo/{todo}/edit', [TodoController::class, 'edit'])->name('todo.edit');

Route::get('/user', [UserController::class, 'index'])->name('user.index');
// route make admin
Route::patch('/user/{user}/makeadmin', [UserController::class, 'makeadmin'])->name('user.makeadmin');
// route remove admin
Route::patch('/user/{user}/removeadmin', [UserController::class, 'removeadmin'])->name('user.removeadmin');

//update data complete
Route::patch('/todo/{todo}/complete', [TodoController::class, 'complete'])->name('todo.complete');
//update data incomplete
Route::patch('/todo/{todo}/incomplete', [TodoController::class, 'uncomplete'])->name('todo.uncomplete');

// delete data todo
Route::delete('/todo/{todo}', [TodoController::class, 'destroy'])->name('todo.destroy');
// deleteallcompleted
Route::delete('/todo', [TodoController::class, 'destroyCompleted'])->name('todo.deleteallcompleted');

// delete user
Route::delete('/user/{user}', [UserController::class, 'destroy'])->name('user.destroy');

// category
Route::get('/category', [CategoryController::class, 'index'])->name('category.index');
Route::resource('category',CategoryController::class)->except(['show']);
// edit category
Route::get('/category/{category}/edit', [CategoryController::class, 'edit'])->name('category.edit');
// delete category
Route::delete('/category/{category}', [CategoryController::class, 'destroy'])->name('category.destroy');


require __DIR__ . '/auth.php';
