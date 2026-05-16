<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Monitor\MonitorController;

Route::post('/monitors', [MonitorController::class, 'store']);
Route::get('/monitors', [MonitorController::class, 'index']);
Route::get('/monitors/{id}/history', [MonitorController::class, 'history']);
