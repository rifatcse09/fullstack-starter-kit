<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::prefix('app')->group(function () {
//    Route::get('/', function () {
//        return "sweet home!";
//        //return view('welcome');
//    });
//
//    // Add more routes here...
//});

//Route::group(array('prefix' => 'admin/'), function() {
//    // Controllers
//    Route::get('/', function () {
//        return "sweet home!";
//        //return view('welcome');
//    });
//});

Route::get('/test', function () {
    return "sweet home!";
    //return view('welcome');
});



