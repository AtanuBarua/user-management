<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UsersController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth', 'verified')->group(function() {
    Route::get('/dashboard', [ UsersController::class, 'index' ])->name('dashboard');
    Route::get('/edit/{id}', [ UsersController::class, 'edit' ])->name('user.edit');
    Route::get('/user/{id}', [ UsersController::class, 'show' ])->name('user.user');
    Route::get('/create', [ UsersController::class, 'create' ])->name('user.create');
    Route::post('/store', [ UsersController::class, 'store' ])->name('user.store');
    Route::post('/update', [ UsersController::class, 'update' ])->name('user.update');
    Route::delete('/trash/{id}', [ UsersController::class, 'trash' ])->name('user.trash');
    Route::get('/trashList', [ UsersController::class, 'trashList' ])->name('user.trashList');
    Route::delete('/delete/{id}', [ UsersController::class, 'delete' ])->name('user.delete');
    Route::post('/restore/{id}', [ UsersController::class, 'restore' ])->name('user.restore');

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
