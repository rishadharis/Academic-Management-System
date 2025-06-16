<?php

use App\Http\Controllers\Auth\AuthAdminController;
use App\Http\Controllers\Pages\Admin\AnnoncementController;
use App\Http\Controllers\Pages\Admin\DashboardAdminController;
use App\Http\Controllers\Pages\Admin\DosenController;
use App\Http\Controllers\Pages\Admin\JadwalController;
use App\Http\Controllers\Pages\Admin\KelasController;
use App\Http\Controllers\Pages\Admin\MahasiswaController;
use App\Http\Controllers\Pages\Admin\MatkulController;
use App\Http\Controllers\Pages\Admin\TahunAkademikController;
use App\Http\Controllers\Pages\DashboardController;
use App\Http\Controllers\Pages\Dosen\AssigmentController;
use App\Http\Controllers\Pages\Dosen\DashboardController as DosenDashboardController;
use App\Http\Controllers\Pages\Dosen\KelasController as DosenKelasController;
use App\Http\Controllers\Pages\Dosen\PenilaianController;
use App\Http\Controllers\Pages\Mahasiswa\AnnoncementController as MahasiswaAnnoncementController;
use App\Http\Controllers\Pages\Mahasiswa\AssigmentController as MahasiswaAssigmentController;
use App\Http\Controllers\Pages\Mahasiswa\DashboardController as MahasiswaDashboardController;
use App\Http\Controllers\Pages\Mahasiswa\JadwalController as MahasiswaJadwalController;
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

Route::get('/', [AuthAdminController::class, 'index'])->name('login');
Route::post('/login', [AuthAdminController::class, 'login'])->name('login.admin.post');
Route::get('/logout', [AuthAdminController::class, 'logout'])->name('logout.admin')->middleware(['auth']);

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
                Route::get('/', [MahasiswaController::class, 'index'])->name('mahasiswa.admin')->middleware(['role:Admin']);
                Route::get('/create', [MahasiswaController::class, 'create'])->name('mahasiswa.admin.create')->middleware(['role:Admin']);
                Route::post('/store', [MahasiswaController::class, 'store'])->name('mahasiswa.admin.store')->middleware(['role:Admin']);
                Route::get('/{id}/edit', [MahasiswaController::class, 'edit'])->name('mahasiswa.admin.edit')->middleware(['role:Admin']);
                Route::put('/{id}/update', [MahasiswaController::class, 'update'])->name('mahasiswa.admin.update')->middleware(['role:Admin']);
                Route::delete('/{id}/destroy', [MahasiswaController::class, 'destroy'])->name('mahasiswa.admin.destroy')->middleware(['role:Admin']);
            });

            Route::prefix('matkul')->group(function () {
                Route::get('/', [MatkulController::class, 'index'])->name('matkul.admin')->middleware(['role:Admin']);
                Route::get('/create', [MatkulController::class, 'create'])->name('matkul.admin.create')->middleware(['role:Admin']);
                Route::post('/store', [MatkulController::class, 'store'])->name('matkul.admin.store')->middleware(['role:Admin']);
                Route::get('/{id}/edit', [MatkulController::class, 'edit'])->name('matkul.admin.edit')->middleware(['role:Admin']);
                Route::put('/{id}/update', [MatkulController::class, 'update'])->name('matkul.admin.update')->middleware(['role:Admin']);
                Route::delete('/{id}/destroy', [MatkulController::class, 'destroy'])->name('matkul.admin.destroy')->middleware(['role:Admin']);
            });

            Route::prefix('tahun-akademik')->group(function () {
                Route::get('/', [TahunAkademikController::class, 'index'])->name('tahun.akademik.admin')->middleware(['role:Admin']);
                Route::get('/create', [TahunAkademikController::class, 'create'])->name('tahun.akademik.admin.create')->middleware(['role:Admin']);
                Route::post('/store', [TahunAkademikController::class, 'store'])->name('tahun.akademik.admin.store')->middleware(['role:Admin']);
                Route::get('/{id}/edit', [TahunAkademikController::class, 'edit'])->name('tahun.akademik.admin.edit')->middleware(['role:Admin']);
                Route::put('/{id}/update', [TahunAkademikController::class, 'update'])->name('tahun.akademik.admin.update')->middleware(['role:Admin']);
                Route::delete('/{id}/destroy', [TahunAkademikController::class, 'destroy'])->name('tahun.akademik.admin.destroy')->middleware(['role:Admin']);
            });

            Route::prefix('kelas')->group(function () {
                Route::get('/', [KelasController::class, 'index'])->name('kelas.admin')->middleware(['role:Admin']);
                Route::get('/create', [KelasController::class, 'create'])->name('kelas.admin.create')->middleware(['role:Admin']);
                Route::post('/store', [KelasController::class, 'store'])->name('kelas.admin.store')->middleware(['role:Admin']);
                Route::get('/{id}/show', [KelasController::class, 'show'])->name('kelas.admin.show')->middleware(['role:Admin']);
                Route::get('/{id}/edit', [KelasController::class, 'edit'])->name('kelas.admin.edit')->middleware(['role:Admin']);
                Route::put('/{id}/update', [KelasController::class, 'update'])->name('kelas.admin.update')->middleware(['role:Admin']);
                Route::delete('/{id}/destroy', [KelasController::class, 'destroy'])->name('kelas.admin.destroy')->middleware(['role:Admin']);

                // add mahasiswa to kelas
                Route::get('getMahasiswa', [KelasController::class, 'getMahasiswa'])->name('kelas.admin.getMahasiswa')->middleware(['role:Admin']);
                Route::post('/addMahasiswaToKelas', [KelasController::class, 'addMahasiswaToKelas'])->name('kelas.admin.addMahasiswaToKelas')->middleware(['role:Admin']);
                Route::delete('/{id}/removeMahasiswaFromKelas', [KelasController::class, 'removeMahasiswaFromKelas'])->name('kelas.admin.removeMahasiswaFromKelas')->middleware(['role:Admin']);
            });

            Route::prefix('jadwal')->group(function () {
                Route::get('/', [JadwalController::class, 'index'])->name('jadwal')->middleware(['role:Admin|Dosen']);
                Route::get('/{id}/create', [JadwalController::class, 'create'])->name('jadwal.create')->middleware(['role:Admin|Dosen']);
                Route::post('/store', [JadwalController::class, 'store'])->name('jadwal.store')->middleware(['role:Admin|Dosen']);
                Route::delete('/{id}/destroy', [JadwalController::class, 'destroy'])->name('jadwal.destroy')->middleware(['role:Admin|Dosen']);
            });

            Route::prefix('penilaian')->group(function () {
                Route::get('/', [PenilaianController::class, 'index'])->name('penilaian.dosen')->middleware(['role:Dosen']);
                Route::get('/{id}/show', [PenilaianController::class, 'show'])->name('penilaian.dosen.show')->middleware(['role:Dosen']);
                Route::post('/store', [PenilaianController::class, 'store'])->name('penilaian.dosen.store')->middleware(['role:Dosen']);
            });

            Route::prefix('anonncement')->group(function () {
                Route::get('/', [AnnoncementController::class, 'index'])->name('annoncement.admin')->middleware(['role:Admin']);
                Route::post('/store', [AnnoncementController::class, 'store'])->name('annoncement.admin.store')->middleware(['role:Admin']);
                Route::delete('/{id}/destroy', [AnnoncementController::class, 'destroy'])->name('annoncement.admin.destroy')->middleware(['role:Admin']);
            });
        });

        // dosen
        Route::prefix('dosen')->group(function () {
            Route::get('/dashboard', [DosenDashboardController::class, 'index'])->name('dashboard.dosen');
            Route::get('/calendar', [DosenDashboardController::class, 'calendar'])->name('calendar.dosen');

            Route::prefix('kelas')->group(function () {
                Route::get('/', [DosenKelasController::class, 'index'])->name('kelas.dosen')->middleware(['role:Dosen']);
                Route::get('/{id}/show', [DosenKelasController::class, 'show'])->name('kelas.dosen.show')->middleware(['role:Dosen']);
                Route::get('/{id}/getAssigment', [DosenKelasController::class, 'getAssigment'])->name('kelas.dosen.getAssigment')->middleware(['role:Dosen']);

                Route::prefix('assigment')->group(function () {
                    Route::get('/{id}/create', [AssigmentController::class, 'create'])->name('kelas.dosen.assigment')->middleware(['role:Dosen']);
                    Route::post('store', [AssigmentController::class, 'store'])->name('kelas.dosen.assigment.store')->middleware(['role:Dosen']);
                    Route::get('/{id}/show', [AssigmentController::class, 'show'])->name('kelas.dosen.assigment.show')->middleware(['role:Dosen']);
                    Route::get('/{id}/edit', [AssigmentController::class, 'edit'])->name('kelas.dosen.assigment.edit')->middleware(['role:Dosen']);
                    Route::put('/{id}/update', [AssigmentController::class, 'update'])->name('kelas.dosen.assigment.update')->middleware(['role:Dosen']);
                    Route::delete('/{id}/destroy', [AssigmentController::class, 'destroy'])->name('kelas.dosen.assigment.destroy')->middleware(['role:Dosen']);

                    Route::get('/{id}/showAssigmentUpload', [AssigmentController::class, 'showAssigmentUpload'])->name('kelas.dosen.showAssigmentUpload.show')->middleware(['role:Dosen']);
                    Route::post('/saveNilai', [AssigmentController::class, 'saveNilai'])->name('kelas.dosen.saveNilai.show')->middleware(['role:Dosen']);
                });
            });

            Route::prefix('jadwal')->group(function () {
                Route::get('/', [JadwalController::class, 'index'])->name('jadwal')->middleware(['role:Admin|Dosen']);
                Route::get('/{id}/create', [JadwalController::class, 'create'])->name('jadwal.create')->middleware(['role:Admin|Dosen']);
                Route::post('/store', [JadwalController::class, 'store'])->name('jadwal.store')->middleware(['role:Admin|Dosen']);
                Route::delete('/{id}/destroy', [JadwalController::class, 'destroy'])->name('jadwal.destroy')->middleware(['role:Admin|Dosen']);
            });
        });

        // mahasiswa
        Route::prefix('mahasiswa')->group(function () {
            Route::get('/dashboard', [MahasiswaDashboardController::class, 'index'])->name('dashboard.mahasiswa')->middleware(['role:Mahasiswa']);

            Route::prefix('kelas')->group(function () {
                Route::get('/{id}/show', [MahasiswaDashboardController::class, 'showKelas'])->name('kelas.mahasiswa.show')->middleware(['role:Mahasiswa']);
            });

            Route::prefix('jadwal')->group(function () {
                Route::get('/', [MahasiswaJadwalController::class, 'index'])->name('jadwal.mahasiswa')->middleware(['role:Mahasiswa']);
                Route::get('/calendar', [MahasiswaJadwalController::class, 'calendar'])->name('jadwal.mahasiswa.calendar')->middleware(['role:Mahasiswa']);
            });

            Route::prefix('assigment')->group(function () {
                Route::get('/', [MahasiswaAssigmentController::class, 'index'])->name('assigment.mahasiswa')->middleware(['role:Mahasiswa']);
                Route::get('/{id}/show', [MahasiswaAssigmentController::class, 'show'])->name('assigment.mahasiswa.show')->middleware(['role:Mahasiswa']);
                Route::post('/store', [MahasiswaAssigmentController::class, 'store'])->name('assigment.mahasiswa.store')->middleware(['role:Mahasiswa']);
                Route::delete('/{id}/destroy', [MahasiswaAssigmentController::class, 'destroy'])->name('assigment.mahasiswa.destroy')->middleware(['role:Mahasiswa']);
            });

            Route::prefix('annoncement')->group(function () {
                Route::get('/', [MahasiswaAnnoncementController::class, 'index'])->name('mahasiswa.annoncement')->middleware(['role:Mahasiswa']);
            });
        });
    });
});
