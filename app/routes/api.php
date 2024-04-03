<?php

use Illuminate\Support\Facades\Route;

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('api/v1')->as('v1:')->group(
    base_path('routes/v1/api.php'),
);
