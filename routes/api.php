<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Barber\ShiftController;
use App\Http\Controllers\Api\Barber\BarberController;
use App\Http\Controllers\Api\Service\ServiceCategorieController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::apiResource('shifts',ShiftController::class);
Route::apiResource('barbers',BarberController::class);
Route::apiResource('servicecategories',ServiceCategorieController::class);


