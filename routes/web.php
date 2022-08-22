<?php

use App\Http\Controllers\Api\V1\JavaController;
use App\Http\Controllers\Api\V1\DigiflazzController;
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

Route::get('/', function () {
    return view('welcome');
});

// Route::get('java/isi-pulsa', function(){ return csrf_token();});
Route::post('java/isi-pulsa', [JavaController::class,'isiPulsa']);

// Route::get('digiflazz/isi-pulsa', function(){ return csrf_token();});
Route::post('digiflazz/isi-pulsa', [DigiflazzController::class,'isiPulsa']);
