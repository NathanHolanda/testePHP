<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Client\CreateClientController;
use App\Http\Controllers\Client\UpdateClientController;
use App\Http\Controllers\Client\DeleteClientController;
use App\Http\Controllers\Client\PaginatedClientController;

use App\Http\Controllers\Product\CreateProductController;
use App\Http\Controllers\Product\UpdateProductController;
use App\Http\Controllers\Product\DeleteProductController;
use App\Http\Controllers\Product\PaginatedProductController;

use App\Http\Controllers\Order\CreateOrderController;
use App\Http\Controllers\Order\UpdateOrderController;
use App\Http\Controllers\Order\DeleteOrderController;
use App\Http\Controllers\Order\PaginatedOrderController;

Route::prefix('clients')->name("clients.")->group(function () {
    Route::post('/', [CreateClientController::class, 'create'])->name('create');
    Route::put('/{id}', [UpdateClientController::class, 'update'])->name('update');
    Route::delete('/{id}', [DeleteClientController::class, 'delete'])->name('delete');
    Route::get('/', [PaginatedClientController::class, 'getPaginatedClients'])->name('paginated');
});

Route::prefix('products')->name("products.")->group(function (){
    Route::post('/', [CreateProductController::class, 'create'])->name('create');
    Route::put('/{id}', [UpdateProductController::class, 'update'])->name('update');
    Route::delete('/{id}', [DeleteProductController::class, 'delete'])->name('delete');
    Route::get('/', [PaginatedProductController::class, 'getPaginatedProducts'])->name('paginated');
});

Route::prefix('orders')->name("orders.")->group(function (){
    Route::post('/', [CreateOrderController::class, 'create'])->name('create');
    Route::put('/{id}', [UpdateOrderController::class, 'update'])->name('update');
    Route::delete('/{id}', [DeleteOrderController::class, 'delete'])->name('delete');
    Route::get('/', [PaginatedOrderController::class, 'getPaginatedOrders'])->name('paginated');
});
