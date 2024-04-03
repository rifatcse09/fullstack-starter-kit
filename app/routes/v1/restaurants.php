<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\Restaurant\RestaurantInfoController;


Route::post('/', [RestaurantInfoController::class,'store']);
Route::get('{id}',[RestaurantInfoController::class,'getRestaurant'])->name('restaurant.id');

?>
