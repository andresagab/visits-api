<?php

use Illuminate\Support\Facades\Route;
use \Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Index');
})->name('home');

Route::get('/visits', function () {
    return Inertia::render('Visits/Index');
})->name('visits.index');
