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

Route::get('/', function () {
    return view('layouts.master');
});

Route::get('dashboard', function () {
    return view('admin.dashboard');
})->name('dashboard');

// SLIDER  
Route::get('slider', ['App\Http\Controllers\Admin\SliderController', 'index'])->name('slider');
Route::any('form', ['App\Http\Controllers\Admin\SliderController', 'form'])->name('form'); # phải để any
Route::post('store', ['App\Http\Controllers\Admin\SliderController', 'store'])->name('store');
Route::post('delete-data', ['App\Http\Controllers\Admin\SliderController', 'deleteData'])->name('deleteData');
Route::post('upload-image', ['App\Http\Controllers\Admin\SliderController', 'uploadImage'])->name('uploadImage');
Route::post('update-status', ['App\Http\Controllers\Admin\SliderController', 'updateStatus'])->name('updateStatus');
Route::post('update-ordering', ['App\Http\Controllers\Admin\SliderController', 'updateOrdering'])->name('updateOrdering');

