<?php

use Illuminate\Support\Facades\Route;
use \Inertia\Inertia;

Route::redirect('/', '/login');

Route::get('/login', function () {
    return Inertia::render('Index');
})->name('login');

Route::post('/login', [\App\Http\Controllers\Web\LoginController::class, 'login'])->name('login.post');

Route::post('/logout', [\App\Http\Controllers\Web\LoginController::class, 'logout'])->name('logout');

Route::get('/visits', function () {
    return Inertia::render('Visits/Index');
})->name('visits.index')->middleware('auth:sanctum');
