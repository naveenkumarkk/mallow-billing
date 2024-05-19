<?php

use App\Http\Controllers\BillingController;
use Illuminate\Support\Facades\Route;
 
Route::get('/',[BillingController::class,'index']);
Route::post('/generate-bill',[BillingController::class,'storeAndGenerateBill']);
Route::get('/search-products',[BillingController::class,'searchProduct']);
Route::post('/download-invouce',[BillingController::class,'downloadInvoicePdf'])->name('download.invoice');;
