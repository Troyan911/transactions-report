<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/tx', \App\Http\Controllers\TransactionController::class);
Route::get('/balance_changes', [\App\Http\Controllers\ReportController::class, 'balance_changes']);
Route::get('/pnl', [\App\Http\Controllers\ReportController::class, 'pnl']);
Route::get('/cf', [\App\Http\Controllers\ReportController::class, 'cashFlows']);
