<?php

use Illuminate\Support\Facades\Route;

Route::name('view.')->group(function(){
    Route::get('/clientes', function () {
        return view('pages/clients');
    })->name('clients');
    Route::get('/produtos', function () {
        return view('pages/products');
    })->name('products');
    Route::get('/pedidos', function () {
        return view('pages/orders');
    })->name('orders');
});

Route::get('/', function () {
    return redirect('clientes');
});
