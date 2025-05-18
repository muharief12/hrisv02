<?php

use App\Http\Controllers\PdfController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/employee/login');
});

Route::get('/pdf/{salary}', PdfController::class)->name('pdf');
