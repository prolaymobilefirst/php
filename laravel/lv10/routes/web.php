<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\lv\LvController;
use App\Http\Controllers\google\FirestoreController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('common/app');
});

Route::prefix('/lv')->controller(LvController::class)->group(function () {
    Route::get('/','index');
});


Route::prefix('/payment')->controller(RazorpayController::class)->group(function () {
    Route::get('/','index');
    Route::get('/paypal','index');
    Route::get('/stripe','index');
    Route::get('/razorpay','index');
});

Route::prefix('/google')->controller(FirestoreController::class)->group(function () {
    Route::get('/firestore','index');
    Route::get('/paypal','index');
    Route::get('/stripe','index');
    Route::get('/razorpay','index');
});