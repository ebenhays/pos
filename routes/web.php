<?php

use App\Http\Controllers\InvoiceController;
use App\Models\DailyTransaction;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/invoice/{batch_no}/print', InvoiceController::class)->name('invoice-print');
