<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Barber\ShiftController;
use App\Http\Controllers\Api\Barber\BarberController;
use App\Http\Controllers\Api\Service\ServiceCategorieController;
use App\Http\Controllers\Api\Service\ServiceController;
use App\Http\Controllers\Api\Customer\CustomerController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::apiResource('shifts',ShiftController::class);
Route::apiResource('barbers',BarberController::class);
Route::apiResource('servicecategories',ServiceCategorieController::class);
Route::apiResource('services',ServiceController::class);
Route::apiResource('customers',CustomerController::class);


