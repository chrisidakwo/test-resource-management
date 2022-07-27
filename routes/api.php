<?php

use App\Http\Controllers\Api\ResourceController;
use Illuminate\Support\Facades\Route;

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

Route::group(['prefix' => 'resources'], function () {
   Route::get('/', [ResourceController::class, 'index'])->name('api.resources.index');
   Route::delete('/', [ResourceController::class, 'delete']);
   Route::post('/create', [ResourceController::class, 'store'])->name('api.resources.store');
   Route::get('/{resource}', [ResourceController::class, 'view']);
   Route::put('/{resource}', [ResourceController::class, 'update']);
});
