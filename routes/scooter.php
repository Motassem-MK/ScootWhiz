<?php

declare(strict_types=1);

use App\Http\Controllers\Scooter\Trip\BeginTripController;
use App\Http\Controllers\Scooter\Trip\EndTripController;
use App\Http\Controllers\Scooter\Trip\UpdateTripController;
use Illuminate\Support\Facades\Route;

Route::prefix('trip')->group(function () {
    Route::post('/begin', BeginTripController::class);
    Route::post('/update', UpdateTripController::class);
    Route::post('/end', EndTripController::class);
});
