<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\google\api\FirestoreController;
use App\Http\Controllers\api\aws\S3FileController;
use App\Http\Controllers\Api\payment\RazorpayController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

//AWS S3
Route::post('/aws/s3upload', [S3FileController::class, 'onUpload']);
Route::get('/aws/s3listfiles', [S3FileController::class, 'onListFiles']);



//Razorpay
Route::post('/razorpay/create_order', [RazorpayController::class, 'onCreateOrder']);