<?php

use Illuminate\Support\Facades\Route;
use  App\Http\Livewire\TypeList;
use  App\Http\Livewire\ItemList;
use  App\Http\Livewire\OrderList;
use  App\Http\Livewire\Cart;
use  App\Http\Livewire\Shopping\Index;

use  App\Http\Controllers\FbLoginController;
use  App\Http\Controllers\ShoppingController;

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


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::group(['middleware' => ['auth', 'verified']], function () {
    Route::get('/type', TypeList::class)->name('type')->middleware('can:manager');
    Route::get('/item', ItemList::class)->name('item')->middleware('can:manager');
    Route::get('/order', OrderList::class)->name('order')->middleware('can:manager');
    Route::get('/shop', Index::class)->name('shop');
    Route::get('/cart', Cart::class)->name('cart')->middleware('can:user');
});

Route::get('/facebook-login', [FbLoginController::class, 'fbLogin']);
// FB 登入 callback
Route::get('/facebook-login-callback', [FbLoginController::class, 'fbLoginCallback']);


Route::prefix('cart_ecpay')->group(function(){

    Route::post('/check', [ShoppingController::class, 'infomationCheck'])->name('check');

    //當消費者付款完成後，綠界會將付款結果參數以幕後(Server POST)回傳到該網址。
    Route::post('/notify', [ShoppingController::class, 'notifyUrl'])->name('notify');

    //付款完成後，綠界會將付款結果參數以幕前(Client POST)回傳到該網址
    Route::post('/return', [ShoppingController::class, 'returnUrl'])->name('return');
});
