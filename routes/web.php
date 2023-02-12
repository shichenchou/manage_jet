<?php

use Illuminate\Support\Facades\Route;
use  App\Http\Livewire\TypeList;
use  App\Http\Livewire\ItemList;
use  App\Http\Livewire\OrderList;
use  App\Http\Livewire\Shopping\Index;
use Illuminate\Support\Facades\DB;
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
    Route::get('/type', TypeList::class)->name('type');
    Route::get('/item', ItemList::class)->name('item');
    Route::get('/order', OrderList::class)->name('order');
    Route::get('/shop', Index::class)->name('shop');
});

Route::get('/test', function () {
    $res = DB::connection('mongodb');
    dd($res);
});
