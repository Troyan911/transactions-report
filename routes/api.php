<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/transaction_create', \App\Http\Controllers\TransactionController::class);
Route::get('/balance_changes', [\App\Http\Controllers\ReportController::class, 'balance_changes']);
Route::get('/balance', [\App\Http\Controllers\ReportController::class, 'balance']);
Route::get('/pnl', [\App\Http\Controllers\ReportController::class, 'pnl']);
Route::get('/cash_flows', [\App\Http\Controllers\ReportController::class, 'cashFlows']);
