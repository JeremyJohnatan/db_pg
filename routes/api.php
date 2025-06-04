<?php

use App\Http\Controllers\AuthController;

Route::middleware('auth:sanctum')->get('/user/stats', [AuthController::class, 'getStats']);