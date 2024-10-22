<?php

use App\Http\Controllers\Auth\AuthAdminController;
use App\Http\Controllers\Pages\Admin\DashboardAdminController;
use App\Http\Controllers\Pages\Admin\DosenController;
use App\Http\Controllers\Pages\Admin\KelasController;
use App\Http\Controllers\Pages\Admin\MahasiswaController;
use App\Http\Controllers\Pages\Admin\MatkulController;
use App\Http\Controllers\Pages\Admin\TahunAkademikController;
use App\Http\Controllers\Pages\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::prefix('login')->group(function () {
    // login admin
    Route::prefix('admin')->group(function () {
        Route::get('/', [AuthAdminController::class, 'index'])->name('login.admin');
        Route::post('/login', [AuthAdminController::class, 'login'])->name('login.admin.post');
        Route::get('/logout', [AuthAdminController::class, 'logout'])->name('logout.admin')->middleware(['auth']);
    });

    // login dosen
});

Route::group(['middleware' => 'auth'], function () {
    Route::prefix('portal')->group(function () {
        // admin
        Route::prefix('admin')->group(function () {
            Route::get('/dashboard', [DashboardAdminController::class, 'index'])->name('dashboard.admin')->middleware(['role:Admin']);

            Route::prefix('dosen')->group(function () {
                Route::get('/', [DosenController::class, 'index'])->name('dosen.admin')->middleware(['role:Admin']);
                Route::get('/create', [DosenController::class, 'create'])->name('dosen.admin.create')->middleware(['role:Admin']);
                Route::post('/store', [DosenController::class, 'store'])->name('dosen.admin.store')->middleware(['role:Admin']);
                Route::get('/{id}/show', [DosenController::class, 'show'])->name('dosen.admin.show')->middleware(['role:Admin']);
                Route::put('/{id}/update', [DosenController::class, 'update'])->name('dosen.admin.update')->middleware(['role:Admin']);
                Route::delete('/{id}/destroy', [DosenController::class, 'destroy'])->name('dosen.admin.destroy')->middleware(['role:Admin']);
            });

            Route::prefix('mahasiswa')->group(function () {
                Route::get('/', [MahasiswaController::class, 'index'])->name('mahasiswa.admin');
                Route::get('/create', [MahasiswaController::class, 'create'])->name('mahasiswa.admin.create');
                Route::post('/store', [MahasiswaController::class, 'store'])->name('mahasiswa.admin.store');
                Route::get('/{id}/edit', [MahasiswaController::class, 'edit'])->name('mahasiswa.admin.edit');
                Route::put('/{id}/update', [MahasiswaController::class, 'update'])->name('mahasiswa.admin.update');
                Route::delete('/{id}/destroy', [MahasiswaController::class, 'destroy'])->name('mahasiswa.admin.destroy');
            });

            Route::prefix('matkul')->group(function () {
                Route::get('/', [MatkulController::class, 'index'])->name('matkul.admin');
                Route::get('/create', [MatkulController::class, 'create'])->name('matkul.admin.create');
                Route::post('/store', [MatkulController::class, 'store'])->name('matkul.admin.store');
                Route::get('/{id}/edit', [MatkulController::class, 'edit'])->name('matkul.admin.edit');
                Route::put('/{id}/update', [MatkulController::class, 'update'])->name('matkul.admin.update');
                Route::delete('/{id}/destroy', [MatkulController::class, 'destroy'])->name('matkul.admin.destroy');
            });

            Route::prefix('tahun-akademik')->group(function () {
                Route::get('/', [TahunAkademikController::class, 'index'])->name('tahun.akademik.admin');
                Route::get('/create', [TahunAkademikController::class, 'create'])->name('tahun.akademik.admin.create');
                Route::post('/store', [TahunAkademikController::class, 'store'])->name('tahun.akademik.admin.store');
                Route::get('/{id}/edit', [TahunAkademikController::class, 'edit'])->name('tahun.akademik.admin.edit');
                Route::put('/{id}/update', [TahunAkademikController::class, 'update'])->name('tahun.akademik.admin.update');
                Route::delete('/{id}/destroy', [TahunAkademikController::class, 'destroy'])->name('tahun.akademik.admin.destroy');
            });

            Route::prefix('kelas')->group(function () {
                Route::get('/', [KelasController::class, 'index'])->name('kelas.admin');
                Route::get('/create', [KelasController::class, 'create'])->name('kelas.admin.create');
                Route::post('/store', [KelasController::class, 'store'])->name('kelas.admin.store');
                Route::get('/{id}/show', [KelasController::class, 'show'])->name('kelas.admin.show');
                Route::get('/{id}/edit', [KelasController::class, 'edit'])->name('kelas.admin.edit');
                Route::put('/{id}/update', [KelasController::class, 'update'])->name('kelas.admin.update');

                // add mahasiswa to kelas
                Route::get('getMahasiswa', [KelasController::class, 'getMahasiswa'])->name('kelas.admin.getMahasiswa');
                Route::post('/addMahasiswaToKelas', [KelasController::class, 'addMahasiswaToKelas'])->name('kelas.admin.addMahasiswaToKelas');
                Route::delete('/{id}/removeMahasiswaFromKelas', [KelasController::class, 'removeMahasiswaFromKelas'])->name('kelas.admin.removeMahasiswaFromKelas');
            });

            // dosen
        });
    });
});
