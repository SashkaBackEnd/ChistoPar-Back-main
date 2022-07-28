<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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
Route::get('admin/bath/{bath_id}', 'App\Http\Controllers\AdminController@bath');
Route::get('admin/specialist/{specialist_id}', 'App\Http\Controllers\AdminController@specialist');
Route::post('changePhotoBath', 'App\Http\Controllers\AdminController@changePhotoBath')->name('changePhotoBath');
Route::post('changePhotoSpecialist', 'App\Http\Controllers\AdminController@changePhotoSpecialist')->name('changePhotoSpecialist');


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
