<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [\App\Http\Controllers\Api\LoginController::class, 'login']);

Route::apiResource('v1/visits', \App\Http\Controllers\Api\V1\VisitController::class)
    ->middleware('auth:sanctum');
