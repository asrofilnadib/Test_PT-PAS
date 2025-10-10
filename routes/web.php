<?php

  use App\Http\Controllers\AuthController;
  use App\Http\Controllers\BarangController;
  use App\Http\Controllers\DashboardController;
  use App\Http\Controllers\ReportController;
  use App\Http\Controllers\TransaksiBarangController;
  use App\Http\Controllers\UserController;
  use Illuminate\Support\Facades\Route;

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

  Route::get('/dashboard', function () {
    return view('dashboard');
  });

  Route::get('/login', [AuthController::class, 'index'])->name('login');
  Route::post('/login/action', [AuthController::class, 'actionLogin'])->name('login.action');
  Route::get('/registrasi', [AuthController::class, 'registrasi'])->name('registrasi');
  Route::post('/registrasi/action', [AuthController::class, 'actionRegistrasi'])->name('registrasi.action');

  Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

  Route::get('/barang', [BarangController::class, 'index'])->name('barang');
  Route::post('/barang/detail', [BarangController::class, 'detail'])->name('barang.detail');
  Route::post('/barang/store', [BarangController::class, 'store'])->name('barang.add');
  Route::post('/barang/update', [BarangController::class, 'update'])->name('barang.update');
  Route::get('/barang/{id}/destroy', [BarangController::class, 'destroy'])->name('barang.destroy');

  Route::get('/transaksi_barang', [TransaksiBarangController::class, 'index'])->name('transaksi_barang');
  Route::post('/transaksi_barang/detail', [TransaksiBarangController::class, 'detail'])->name('transaksi_barang.detail');
  Route::post('/transaksi_barang/store', [TransaksiBarangController::class, 'store'])->name('transaksi_barang.add');
  Route::post('/transaksi_barang/update', [TransaksiBarangController::class, 'update'])->name('transaksi_barang.update');
  Route::get('/transaksi_barang/{id}/destroy', [TransaksiBarangController::class, 'destroy'])->name('transaksi_barang.destroy');

  Route::get('/report', [ReportController::class, 'index'])->name('report');
  Route::post('/report/filter', [ReportController::class, 'filter'])->name('report.filter');
  Route::post('/report/send', [ReportController::class, 'mail'])->name('report.mail');
  Route::post('/report/detail', [ReportController::class, 'detail'])->name('report.detail');
  Route::post('/report/store', [ReportController::class, 'store'])->name('report.add');
  Route::post('/report/update', [ReportController::class, 'update'])->name('report.update');
  Route::get('/report/{id}/destroy', [ReportController::class, 'destroy'])->name('report.destroy');

  Route::get('/user', [UserController::class, 'index'])->name('user');
  Route::post('/user/filter', [UserController::class, 'filter'])->name('user.filter');
  Route::post('/user/detail', [UserController::class, 'detail'])->name('user.detail');
  Route::post('/user/store', [UserController::class, 'store'])->name('user.add');
  Route::post('/user/update/{id}', [UserController::class, 'update'])->name('user.update');
  Route::get('/user/{id}/destroy', [UserController::class, 'destroy'])->name('user.destroy');

  Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

  Route::get('/', function () {
    return view('login');
  });
