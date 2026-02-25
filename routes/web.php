<?php

use App\Http\Controllers\BeasiswaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrmawaController;
use App\Http\Controllers\CompetitionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/profile/{slug}', [ProfileController::class, 'show'])->name('profile.show');
Route::get('/ormawa', [OrmawaController::class, 'index'])->name('ormawa.index');
Route::get('/ormawa/{slug}', [OrmawaController::class, 'show'])->name('ormawa.show');
Route::get('/kompetisi', [CompetitionController::class, 'index'])->name('competition.index');
Route::get('/beasiswa', [BeasiswaController::class, 'index'])->name('beasiswa.index');
Route::get('/layanan/{slug}', [ServiceController::class, 'show'])->name('service.show');
Route::get('/unduhan', [\App\Http\Controllers\DownloadController::class, 'index'])->name('download.index');

Route::get('/lang/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');
