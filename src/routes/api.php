<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Client\CreateClientController;
use App\Http\Controllers\Client\UpdateClientController;
use App\Http\Controllers\Client\DeleteClientController;
use App\Http\Controllers\Client\PaginatedClientController;

Route::post('/clients', [CreateClientController::class, 'create'])->name('clients.create');
Route::put('/clients/{id}', [UpdateClientController::class, 'update'])->name('clients.update');
Route::delete('/clients/{id}', [DeleteClientController::class, 'delete'])->name('clients.delete');
Route::get('/clients', [PaginatedClientController::class, 'getPaginatedClients'])->name('clients.paginated');
