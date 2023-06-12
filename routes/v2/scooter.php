<?php

declare(strict_types=1);

use App\Http\Controllers\Scooter\Trip\BeginTripController;
use App\Http\Controllers\Scooter\Trip\EndTripController;
use App\Http\Controllers\Scooter\Trip\UpdateTripController;
use Illuminate\Support\Facades\Route;

Route::post('{scooter}/trips', BeginTripController::class);
Route::put('{scooter}/trips/{trip}', UpdateTripController::class);
Route::post('{scooter}/trips/{trip}/end', EndTripController::class);
