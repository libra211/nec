<?php

use App\Http\Controllers\Api\ApiContactController;
use App\Http\Controllers\Api\ApiDownloadController;
use App\Http\Controllers\Api\ApiLikeController;
use App\Http\Controllers\Api\ApiLocationsController;
use App\Http\Controllers\Api\ApiNewsletterController;
use App\Http\Controllers\Api\ApiResultsController;
use App\Http\Controllers\Api\ApiVerifyController;
use App\Http\Controllers\Api\ApiVoterLookupController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/contact', [ApiContactController::class, 'store']);
    Route::post('/newsletter', [ApiNewsletterController::class, 'store']);
    Route::post('/like', [ApiLikeController::class, 'store']);
    Route::post('/download', [ApiDownloadController::class, 'store']);
    Route::get('/locations', [ApiLocationsController::class, 'index']);

    // API key protected routes
    Route::middleware('api.key')->group(function () {
        Route::get('/verify', [ApiVerifyController::class, 'show']);
        Route::get('/voter-lookup', [ApiVoterLookupController::class, 'show']);
        Route::get('/results', [ApiResultsController::class, 'index']);
    });
});
