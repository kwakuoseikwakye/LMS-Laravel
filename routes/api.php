<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookController;
use Illuminate\Support\Facades\Route;

Route::get('/v1', function () {
      return response()->json([
          'app_name' => 'Library Management System',
          'api_version' => '1.0.0',
      ]);
  });
Route::group(['prefix' => 'v1'], function () {
      Route::post("login", [AuthController::class, "login"]);
      Route::post("signup", [AuthController::class, "signup"]);
});

Route::group(['prefix' => 'v1', 'middleware' => 'auth:sanctum'], function () {
      Route::apiResource('books', BookController::class);
});

Route::fallback(function () {
      return apiErrorResponse('Route Not Found.', 404);
});
