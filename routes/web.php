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

$prefix = 'admin';

Route::group(['prefix' => $prefix], function() use($prefix){
    Route::name($prefix .'.')->group(function () {
        Route::get('login', ['\App\Http\Controllers\Auth\LoginController', 'showFormLogin'])->name('login.form')->middleware('check.login');
        Route::post('login', ['\App\Http\Controllers\Auth\LoginController', 'login'])->name('login');
        Route::post('logout', ['\App\Http\Controllers\Auth\LoginController', 'logout'])->name('logout');
    });
});


Route::group(['prefix' => $prefix,'middleware' => ['permission.admin']], function() use($prefix){
    Route::name($prefix .'.')->group(function () {
        
        // DASHBOARD
        Route::get('dashboard', function () { return view('admin.dashboard');})->name('dashboard');

        //SLIDER
        Route::name('slider.')->group(function () {
            Route::get('slider', ['App\Http\Controllers\Admin\SliderController', 'index'])->name('index');
            Route::any('slider/form', ['App\Http\Controllers\Admin\SliderController', 'form'])->name('form'); # phải để any
            Route::post('store', ['App\Http\Controllers\Admin\SliderController', 'store'])->name('store');
            Route::post('delete-data', ['App\Http\Controllers\Admin\SliderController', 'deleteData'])->name('deleteData');
            Route::post('upload-image', ['App\Http\Controllers\Admin\SliderController', 'uploadImage'])->name('uploadImage');
            Route::post('update-status', ['App\Http\Controllers\Admin\SliderController', 'updateStatus'])->name('updateStatus');
            Route::post('update-ordering', ['App\Http\Controllers\Admin\SliderController', 'updateOrdering'])->name('updateOrdering');
        });
    });
});









// SLIDER  
// // Route::get('slider', ['App\Http\Controllers\Admin\SliderController', 'index'])->name('slider');
// Route::any('form', ['App\Http\Controllers\Admin\SliderController', 'form'])->name('form'); # phải để any
// Route::post('store', ['App\Http\Controllers\Admin\SliderController', 'store'])->name('store');
// Route::post('delete-data', ['App\Http\Controllers\Admin\SliderController', 'deleteData'])->name('deleteData');
// Route::post('upload-image', ['App\Http\Controllers\Admin\SliderController', 'uploadImage'])->name('uploadImage');
// Route::post('update-status', ['App\Http\Controllers\Admin\SliderController', 'updateStatus'])->name('updateStatus');
// Route::post('update-ordering', ['App\Http\Controllers\Admin\SliderController', 'updateOrdering'])->name('updateOrdering');

