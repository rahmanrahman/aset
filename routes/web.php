<?php

use App\Http\Controllers\AplikasiController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\IpAddressController;
use App\Http\Controllers\JenisController;
use App\Http\Controllers\KerusakanKomputerController;
use App\Http\Controllers\KomponenController;
use App\Http\Controllers\KomponenDetailController;
use App\Http\Controllers\KomputerController;
use App\Http\Controllers\PergantianKomputerController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SistemOperasiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendorController;
use App\Models\KerusakanKomputer;
use Illuminate\Support\Facades\Auth;
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


Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('roles/data', [RoleController::class, 'data'])->name('roles.data');
    Route::post('roles/get', [RoleController::class, 'get'])->name('roles.get');
    Route::DELETE('roles/remove-permission', [RoleController::class, 'removePermission'])->name('roles.remove-permission');
    Route::post('roles/add-permission', [RoleController::class, 'addPermission'])->name('roles.add-permission');
    Route::resource('roles', RoleController::class)->except('create', 'show', 'edit', 'update');


    Route::get('permissions/data', [PermissionController::class, 'data'])->name('permissions.data');
    Route::post('permissions/get', [PermissionController::class, 'get'])->name('permissions.get');
    Route::post('permissions/getByRole', [PermissionController::class, 'getByRole'])->name('permissions.getByRole');
    Route::resource('permissions', PermissionController::class)->except('create', 'show', 'edit', 'update');


    Route::get('users/data', [UserController::class, 'data'])->name('users.data');
    Route::resource('users', UserController::class)->except('create', 'edit', 'update');

    Route::get('ipaddress/data', [IpAddressController::class, 'data'])->name('ipaddress.data');
    Route::resource('ipaddress', IpAddressController::class)->except('create', 'edit', 'update');

    Route::get('jenis/data', [JenisController::class, 'data'])->name('jenis.data');
    Route::resource('jenis', JenisController::class)->except('create', 'edit', 'update');

    Route::get('brand/data', [BrandController::class, 'data'])->name('brand.data');
    Route::resource('brand', BrandController::class)->except('create', 'edit', 'update');

    Route::get('department/data', [DepartmentController::class, 'data'])->name('department.data');
    Route::resource('department', DepartmentController::class)->except('create', 'edit', 'update');

    Route::get('sistem_operasi/data', [SistemOperasiController::class, 'data'])->name('sistem_operasi.data');
    Route::resource('sistem_operasi', SistemOperasiController::class)->except('create', 'edit', 'update', 'show');
    Route::get('sistem-operasi/detail', [SistemOperasiController::class, 'detail'])->name('sistem-operasi.detail');

    Route::get('aplikasi/data', [AplikasiController::class, 'data'])->name('aplikasi.data');
    Route::resource('aplikasi', AplikasiController::class)->except('create', 'edit', 'update');

    Route::get('vendor/data', [VendorController::class, 'data'])->name('vendor.data');
    Route::get('vendor/getById', [VendorController::class, 'getById'])->name('vendor.getById');
    Route::resource('vendor', VendorController::class)->except('create', 'edit', 'update');

    Route::resource('komponen', KomponenController::class)->except('show');
    Route::resource('komponen-detail', KomponenDetailController::class)->except('show');

    Route::resource('komputer', KomputerController::class);
    Route::resource('pergantian-komputer', PergantianKomputerController::class);
    Route::resource('kerusakan-komputer', KerusakanKomputerController::class);
});
