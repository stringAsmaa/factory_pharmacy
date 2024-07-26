<?php

use App\Http\Controllers\FactoryController;
use App\Http\Controllers\PharmacyController;
use App\Http\Controllers\testController;
use App\Http\Middleware\FactoryMiddleware;
use App\Http\Middleware\PharmacyMiddleware;
use App\Notifications\newOrder;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Route;

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


///////////////////////////////////////////////////////////////////////////////////////////لصاحب المستودع
          Route::post('/regester/factory',[FactoryController::class,'regester']);


  Route::post('/login/factory',[FactoryController::class,'login']);

        Route::middleware(['auth:sanctum','FactoryMiddleware'])->group(function(){
            Route::get('/logout/factory',[FactoryController::class,'logout']);
        });

 


  Route::middleware(['auth:sanctum', 'FactoryMiddleware'])->get('/user/factory', function (Request $request) {
      return $request->user();
 });

////////////////////////////////////////////////////////////////////////////////////////للصيدلي 

 Route::post('/regester/pharmacy',[PharmacyController::class,'regester']);

 Route::post('/login/pharmacy',[PharmacyController::class,'login']);


           Route::middleware([ 'auth:sanctum','PharmacyMiddleware'])->group(function(){
    Route::get('/logout/pharmacy',[PharmacyController::class,'logout']);
});

Route::middleware(['auth:sanctum', 'PharmacyMiddleware'])->get('/user/pharmacy', function (Request $request) {
    return $request->user();
});
////////////////////////////////////////////////////////////////////////////////////////////////////

Route::post('/insert/factory',[FactoryController::class,'insert']);

Route::get('/show/categories',[PharmacyController::class,'show']);
Route::post('/show_by_id/pharmacist',[PharmacyController::class,'show_by_id']);



Route::post('/search/pharmacist',[PharmacyController::class,'search']);
Route::post('/search/factory',[FactoryController::class,'search']);


Route::post('/select/pharmacist',[PharmacyController::class,'select']);
Route::post('/select/factory',[FactoryController::class,'select']);







Route::post('/order/pharmacist',[PharmacyController::class,'order']);

Route::post('/factory_orders/pharmacist',[PharmacyController::class,'factory_orders']);



Route::get('/show_orders/pharmacist',[PharmacyController::class,'show_orders']);
Route::get('/show_all_orders/pharmacy',[PharmacyController::class,'show_all_orders']);



Route::get('/show_orders/factory',[FactoryController::class,'show_orders']);
Route::get('/show_all_orders/factory',[FactoryController::class,'show_all_orders']);
Route::post('/edit_orders/factory',[FactoryController::class,'edit_orders']);



Route::get('/notify/factory',[FactoryController::class,'notify']);
Route::get('/notify/pharmacy',[PharmacyController::class,'notify']);


Route::post('/add_amount/factory',[FactoryController::class,'add_amount']);


Route::get('/test',[testController::class,'test']);
