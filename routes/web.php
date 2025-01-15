<?php

use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\RentalController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('rental');
});
    // Customers Section
    Route::get('/data/{type}', [RentalController::class, 'index'])->name('rental');

    // Invoices Section



Route::get('/add',[RentalController::class,'add'])->name('add');
Route::post('/product_submit', [RentalController::class, 'store'])->name('product_submit');
Route::get('/list',[RentalController::class,'allRecord'])->name('list');
Route::post('/enquiry_submit', [EnquiryController::class, 'store'])->name('enquiries.store');
Route::post('/enquiries_edit', [EnquiryController::class, 'update'])->name('enquiries.edit');
Route::delete('/enquiries_destroy/{id}', [EnquiryController::class, 'destroy'])->name('enquiries.destroy');
