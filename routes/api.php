<?php

  use App\Http\Controllers\API\APIBarangController;
  use App\Http\Controllers\API\APITransaksiBarangController;
  use App\Http\Controllers\BarangController;
  use Illuminate\Http\Request;
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

  Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
  });

  Route::prefix('v1')->group(function () {
    Route::prefix('barang')->group(function () {
      Route::get('/{id}', [APIBarangController::class, 'show'])
        ->name('api.detail.barang');
    });

    Route::prefix('transaksi')->group(function () {
      Route::get('/{id}', [APITransaksiBarangController::class, 'show'])
        ->name('api.detail.transaksi');
    });
  });
