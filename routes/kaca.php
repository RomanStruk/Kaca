<?php

use Kaca\Http\Controllers\ActionRecorderController;
use Kaca\Http\Controllers\CashierController;
use Kaca\Http\Controllers\CashierUserController;
use Kaca\Http\Controllers\CashRegisterController;
use Kaca\Http\Controllers\DashboardController;
use Kaca\Http\Controllers\EntryController;
use Kaca\Http\Controllers\ReceiptsController;
use Kaca\Http\Controllers\RefundReceiptsController;
use Kaca\Http\Controllers\ReportsController;
use Kaca\Http\Controllers\ShiftsController;
use Illuminate\Support\Facades\Route;

// dashboard
Route::get('/kaca/', DashboardController::class)
    ->name('kaca.index');

//Користувачі
Route::get('/kaca/cashier-users/', [CashierUserController::class, 'index'])
    ->name('kaca.cashier-users.index');
Route::get('/kaca/cashier-users/create/', [CashierUserController::class, 'create'])
    ->name('kaca.cashier-users.create');
Route::post('/kaca/cashier-users/', [CashierUserController::class, 'store'])
    ->name('kaca.cashier-users.store');
Route::get('/kaca/cashier-users/{id}/', [CashierUserController::class, 'edit'])
    ->name('kaca.cashier-users.edit');
Route::post('/kaca/cashier-users/{id}/', [CashierUserController::class, 'update'])
    ->name('kaca.cashier-users.update');
Route::delete('/kaca/cashier-users/{id}/', [CashierUserController::class, 'destroy'])
    ->name('kaca.cashier-users.destroy');

// Каса
Route::get('/kaca/cash-registers/', [CashRegisterController::class, 'index'])
    ->name('kaca.cash-registers.index');
Route::get('/kaca/cash-registers/create', [CashRegisterController::class, 'create'])
    ->name('kaca.cash-registers.create');
Route::post('/kaca/cash-registers/', [CashRegisterController::class, 'store'])
    ->name('kaca.cash-registers.store');
Route::delete('/kaca/cash-registers/{cashRegister}/', [CashRegisterController::class, 'destroy'])
    ->name('kaca.cash-registers.destroy');

// Касир
Route::get('/kaca/cashiers/', [CashierController::class, 'index'])
    ->name('kaca.cashiers.index');
Route::get('/kaca/cashiers/create/', [CashierController::class, 'create'])
    ->name('kaca.cashiers.create');
Route::post('/kaca/cashiers/create/', [CashierController::class, 'store'])
    ->name('kaca.cashiers.store');
Route::delete('/kaca/cashiers/{cashier}/', [CashierController::class, 'destroy'])
    ->name('kaca.cashiers.destroy');


Route::post('/kaca/cashier/cash-register/update/', [CashRegisterController::class, 'update'])
    ->name('kaca.cashier.cash-register.update');

// Робочі зміни shifts
 Route::get('/kaca/shifts/', [ShiftsController::class, 'index'])
     ->name('kaca.shifts.index');
Route::post('/kaca/shifts/', [ShiftsController::class, 'store'])
    ->name('kaca.shifts.store');
Route::delete('/kaca/shifts/', [ShiftsController::class, 'destroy'])
    ->name('kaca.shifts.destroy');

// Моя каса
// Продаж create receipts
Route::get('/kaca/receipts/', [ReceiptsController::class, 'index'])
    ->name('kaca.receipts.index');
Route::get('/kaca/receipts/create', [ReceiptsController::class, 'create'])
    ->name('kaca.receipts.create');
Route::post('/kaca/receipts/create/', [ReceiptsController::class, 'store'])
    ->name('kaca.receipts.store');
Route::get('/kaca/receipts/{receipt}/', [ReceiptsController::class, 'show'])
    ->name('kaca.receipts.show');
// Повернення refund receipt
Route::post('/kaca/refund-receipts/{receipt}/', [RefundReceiptsController::class, 'store'])
    ->name('kaca.refund-receipts.store');

Route::get('/kaca/reports/', [ReportsController::class, 'index'])
    ->name('kaca.reports.index');
Route::post('/kaca/reports/', [ReportsController::class, 'store'])
    ->name('kaca.reports.store');
Route::get('/kaca/reports/{report}/', [ReportsController::class, 'show'])
    ->name('kaca.reports.show');

// entries
Route::get('/kaca/entries/', EntryController::class)
    ->name('kaca.entries.index');
Route::get('/kaca/action-recorders/', ActionRecorderController::class)
    ->name('kaca.action-recorders.index');
