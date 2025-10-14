<?php

  use App\Http\Controllers\AuthController;
  use App\Http\Controllers\BarangController;
  use App\Http\Controllers\DashboardController;
  use App\Http\Controllers\ReportController;
  use App\Http\Controllers\TransaksiBarangController;
  use App\Http\Controllers\UserController;
  use Illuminate\Support\Facades\Auth;
  use Illuminate\Support\Facades\Route;

  /*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  */

  Route::get('/', function () {
    return redirect()->to('/login');
  });

  // Authentication Routes
  Route::get('/login', [AuthController::class, 'index'])
    ->name('login');
  Route::post('/login/action', [AuthController::class, 'actionLogin'])
    ->name('login.action');
  Route::get('/logout', [AuthController::class, 'logout'])
    ->name('logout');

  // ===============================================
  // Protected Routes (hanya bisa diakses setelah login)
  // ===============================================
  Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
      ->name('dashboard');
    Route::get('/dashboard/filter', [DashboardController::class, 'filter'])
      ->name('dashboard.filter');

    // Barang
    Route::group(['prefix' => 'barang'], function () {
      Route::get('/', [BarangController::class, 'index'])
        ->name('barang');
      Route::post('/detail', [BarangController::class, 'detail'])
        ->name('barang.detail');
      Route::post('/store', [BarangController::class, 'store'])
        ->name('barang.add');
      Route::post('/update', [BarangController::class, 'update'])
        ->name('barang.update');
      Route::get('/{id}/destroy', [BarangController::class, 'destroy'])
        ->name('barang.destroy');
    });

    // Transaksi Barang
    Route::group(['prefix' => 'transaksi_barang'], function () {
      Route::get('/', [TransaksiBarangController::class, 'index'])
        ->name('transaksi_barang');
      Route::post('/detail', [TransaksiBarangController::class, 'detail'])
        ->name('transaksi_barang.detail');
      Route::post('/store', [TransaksiBarangController::class, 'store'])
        ->name('transaksi_barang.add');
      Route::post('/update', [TransaksiBarangController::class, 'update'])
        ->name('transaksi_barang.update');
      Route::post('/update-status', [TransaksiBarangController::class, 'updateStatus'])
        ->name('transaksi_barang.updateStatus');
      Route::get('/{id}/destroy', [TransaksiBarangController::class, 'destroy'])
        ->name('transaksi_barang.destroy');
      Route::post('/status/{id}', [TransaksiBarangController::class, 'status'])
        ->name('transaksi_barang.status');
    });

    // Laporan Barang
    Route::group(['prefix' => 'report'], function () {
      Route::get('/', [ReportController::class, 'index'])
        ->name('report');
      Route::post('/filter', [ReportController::class, 'filter'])
        ->name('report.filter');
      Route::post('/send', [ReportController::class, 'mail'])
        ->name('report.mail');
      Route::post('/detail', [ReportController::class, 'detail'])
        ->name('report.detail');
      Route::post('/store', [ReportController::class, 'store'])
        ->name('report.add');
      Route::post('/update', [ReportController::class, 'update'])
        ->name('report.update');
      Route::get('/{id}/destroy', [ReportController::class, 'destroy'])
        ->name('report.destroy');
      Route::get('/print', [ReportController::class, 'pdf'])
        ->name('report.pdf');
    });

    // User Management
    Route::group(['prefix' => 'user'], function () {
      Route::get('/', [UserController::class, 'index'])
        ->name('user');
      Route::post('/filter', [UserController::class, 'filter'])
        ->name('user.filter');
      Route::post('/detail', [UserController::class, 'detail'])
        ->name('user.detail');
      Route::post('/store', [UserController::class, 'store'])
        ->name('user.add');
      Route::post('/update', [UserController::class, 'update'])
        ->name('user.update');
      Route::get('/{id}/destroy', [UserController::class, 'destroy'])
        ->name('user.destroy');
    });
  });

  Auth::routes();

  Route::get('/home', 'HomeController@index')
    ->name('home');
