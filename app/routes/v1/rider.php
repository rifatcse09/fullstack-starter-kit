<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RiderInfoController;


Route::post('/', [RiderInfocontroller::class,'store']);
Route::get('{id}',[RiderInfoController::class,'getRiders'])->name('riders.id');

?>
