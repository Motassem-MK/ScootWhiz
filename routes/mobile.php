<?php

declare(strict_types=1);

use App\Http\Controllers\Mobile\ScootersListingController;
use Illuminate\Support\Facades\Route;

Route::get('/scooters', ScootersListingController::class);
