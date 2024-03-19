<?php

use App\Http\Controllers\V1\AlbumController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('v1')->group(function() {
    Route::apiResource('album', AlbumController::class);
});