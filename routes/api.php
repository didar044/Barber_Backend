<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Barber\ShiftController;
use App\Http\Controllers\Api\Barber\BarberController;
use App\Http\Controllers\Api\Service\ServiceCategorieController;
use App\Http\Controllers\Api\Service\ServiceController;
use App\Http\Controllers\Api\Customer\CustomerController;
use App\Http\Controllers\Api\Appointment\AppointmentController;
use App\Http\Controllers\Api\Appointment\Appointment_ServiceController;
use App\Http\Controllers\Api\Payment\PaymentController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::apiResource('shifts',ShiftController::class);
Route::apiResource('barbers',BarberController::class);
Route::apiResource('servicecategories',ServiceCategorieController::class);
Route::apiResource('services',ServiceController::class);
Route::apiResource('customers',CustomerController::class);
Route::apiResource('appointments',AppointmentController::class);
Route::apiResource('appointmentservices',Appointment_ServiceController::class);
Route::patch('/appointments/{id}/status', [AppointmentController::class, 'updateStatus']);
Route::apiResource('payments',PaymentController::class);



