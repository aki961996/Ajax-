<?php

use App\Http\Controllers\ProductAjaxController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::resource('products', ProductAjaxController::class);
