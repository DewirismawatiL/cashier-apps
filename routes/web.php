<?php

use App\Exports\SellingExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PenjualanController;

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');

    Route::resource('products', ProductController::class);
    Route::put('/product/{id}/updateStock', [ProductController::class, 'updateStock'])->name('products.updateStock');
    Route::post('/storeCart', [PenjualanController::class, 'cart'])->name('store.cart');
    Route::get('/transaction/{id}/pdf', [PenjualanController::class, 'CetakPdf'])->name('formatpdf');
    Route::patch('/orderMember', [PenjualanController::class, 'checkMember'])->name('orderMember');
    Route::resource('pembelians', PenjualanController::class);
    Route::resource('users', UserController::class);
    Route::get('/export-excel', function () {
        return Excel::download(new SellingExport, 'DokumenExcel.xlsx');
    })->name('formatexcel');
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
});

Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return view('auth.login');
    })->name('login');
    Route::post('/login', [UserController::class, 'authLogin'])->name('authLogin');
});

// Route::get('fileUpload' , [
//     'as' => 'products.store',
//     'uses' => 'ProductController@fileUpload'
// ]);
