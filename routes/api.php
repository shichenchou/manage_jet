<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use  App\Http\Controllers\CommodityItemController;
use  App\Http\Controllers\CommodityTypeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


//商品類型API
Route::apiResource('CommodityType', CommodityTypeController::class);

//商品內容API
Route::apiResource('CommodityItem', CommodityItemController::class);
