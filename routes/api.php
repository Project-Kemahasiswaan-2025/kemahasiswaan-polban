<?php

use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\VideoController;
use App\Http\Controllers\Api\OrmawaController;
use App\Http\Controllers\Api\CompetitionController;
use Illuminate\Support\Facades\Route;

Route::prefix('api')->group(function () {
    Route::get('/banners', [BannerController::class, 'index']);
    Route::get('/videos', [VideoController::class, 'index']);
    Route::get('/ormawa', [OrmawaController::class, 'index']);
    Route::get('/competitions', [CompetitionController::class, 'index']);
});
