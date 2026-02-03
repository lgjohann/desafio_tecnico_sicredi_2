<?php

use App\Http\Controllers\AssociateController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::prefix('auth')->group(function () {
    // públicas
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // com middleware de validação do JWT
    Route::middleware('jwt')->group(function () {
        Route::get('/user', [AuthController::class, 'getUser']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

Route::prefix('associates')->group(function () {
    Route::middleware('jwt')->group(function () {
        Route::post('/', [AssociateController::class, 'createAssociate']);
        Route::get('/', [AssociateController::class, 'getAssociates']);
        Route::put('/{id}', [AssociateController::class, 'updateAssociate']);
        Route::delete('/{id}', [AssociateController::class, 'deleteAssociate']);
    });
});
