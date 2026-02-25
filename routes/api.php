<?php

use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\VideoController;
use App\Http\Controllers\Api\OrmawaController;
use App\Http\Controllers\Api\CompetitionController;
use App\Http\Controllers\Api\RunningTextController;
use App\Http\Controllers\Api\PosterController;
use App\Http\Controllers\Api\BeasiswaController;
use Illuminate\Support\Facades\Route;

Route::get('/banners', [BannerController::class, 'index']);
Route::get('/videos', [VideoController::class, 'index']);
Route::get('/ormawa', [OrmawaController::class, 'index']);
Route::get('/ormawa/groups', [OrmawaController::class, 'groups']);
Route::get('/competitions', [CompetitionController::class, 'index']);
Route::get('/running-texts', [RunningTextController::class, 'index']);
Route::get('/posters', [PosterController::class, 'index']);
Route::get('/beasiswa', [BeasiswaController::class, 'index']);
