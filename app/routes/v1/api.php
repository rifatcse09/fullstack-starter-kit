<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;


//Route::prefix('riders')->as('riders:')->group(
//    base_path('routes/v1/rider.php'),
//);

Route::prefix('restaurants')->as('restaurants:')->group(
    base_path('routes/v1/restaurants.php'),
);


?>
