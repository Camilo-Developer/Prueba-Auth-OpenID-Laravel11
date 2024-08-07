<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Redirect\RedirectController;



Route::get('/auth/azure', [RedirectController::class, 'azureLogin'])->name('auth.azure');
Route::get('/auth/azure/callback', [RedirectController::class, 'azureCallback']);



Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
