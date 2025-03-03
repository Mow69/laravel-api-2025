<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfilController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'authenticate']);

Route::apiResources([
    'profils' => ProfilController::class
], ['only' => ['index']]);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::apiResources([
        'profils' => ProfilController::class,
    ], ['only' => ['store', 'update', 'destroy']]);
});