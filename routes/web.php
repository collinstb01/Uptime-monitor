<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Monitor\MonitorController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/monitors', [MonitorController::class, 'store']);
Route::get('/monitors', [MonitorController::class, 'index']);
Route::get('/monitors/{id}/history', [MonitorController::class, 'history']);