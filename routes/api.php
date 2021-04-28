<?php

use App\Http\Controllers\AnimalController;
use App\Http\Controllers\TypeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Animal\AnimalLikeController;

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

/* Route::middleware('auth:api')->get('/user', function (Request $request) {
     return $request->user();
});*/


Route::apiResource('animals', AnimalController::class);


Route::apiResource('types', TypeController::class);

//要求附上擁有user-info的token
Route::middleware(['auth:api', 'scope:user-info'])->get('/user', function (Request $request) {
    return $request->user();
});

//路由animals.like(中間可加上id->post表示目前登入使用者喜歡id動物/get表示查看所有追蹤id的會員)
Route::apiResource('animals.likes', AnimalLikeController::class)->only([ //只保留index和store兩種路由設定
    'index', 'store'
]);
