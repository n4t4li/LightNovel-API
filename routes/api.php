<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\LightNovelController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| All routes here have /api prefix automatically 
| Example: http://localhost:/api/lightnovels
*/

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [RegisterController::class, 'login']);
Route::resource('products',ProductController::class);
Route::middleware('auth:sanctum')->group(function () {
    
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', [RegisterController::class, 'logout']);

    Route::post('/lightnovels', [LightNovelController::class, 'store']);
    Route::put('/lightnovels/{id}', [LightNovelController::class, 'update']);
    Route::delete('/lightnovels/{id}', [LightNovelController::class, 'destroy']);
});

Route::get('/lightnovels', [LightNovelController::class, 'index']);  // list
Route::get('/lightnovels/{id}', [LightNovelController::class, 'show']); // show one
