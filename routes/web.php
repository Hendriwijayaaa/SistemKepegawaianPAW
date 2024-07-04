<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EvaluasiLaporanController;
use App\Http\Controllers\LaporanKinerjaController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PelatihanPegawaiController;
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
Route::get("/test", [AbsensiController::class, 'test']);

    Route::get('/dokumen', [DashboardController::class, 'doc']);

    Route::get('/login', [Authcontroller::class, 'login'])->name('login.index');
    Route::get('/logout', [Authcontroller::class, 'logout'])->name('logout')->middleware('auth');
    Route::post('/login/add', [Authcontroller::class, 'createLogin'])->name('login.store');
    Route::get("/verifikasi/auth/{email}/{token}", [AuthController::class, 'activeMail'])->name('active.mail');
    Route::delete("/verifikasi/{id}/setiry/hapus", [AuthController::class, 'destroy'])->name('masterakun.delete');


    Route::middleware(['auth','checkRole:admin'])->group(function () {
        Route::get("/pegawai/tambah", [PegawaiController::class, "create"])->name('pegawai.create');
        Route::get("/pegawai/edit/{id}", [PegawaiController::class, "edit"])->name('pegawai.edit');
        Route::post("/pegawai/tambah/push", [PegawaiController::class, "store"])->name('pegawai.store');
        Route::post("/pegawai/edit/{id}/update", [PegawaiController::class, "update"])->name('pegawai.update');
        Route::delete("/pegawai/item/{id}/hapus", [PegawaiController::class, "destroy"])->name('pegawai.hapus');

        Route::get('/maters/akun', [AuthController::class, 'masterAkun'])->name('masterakun.index');
        Route::get('/maters/akun/all', [Authcontroller::class, 'masterAkunCreate'])->name('masterakun.create');
        Route::post('/maters/akun/post', [Authcontroller::class, 'masterAkunStore'])->name('masterakun.store');

        Route::get('/udpatedara/{id}/addting', [Authcontroller::class, 'udpateTing'])->name('masterakun.getView');
        Route::post('/udpatedara/{id}/post-update', [Authcontroller::class, 'masterAkunUpdateting'])->name('masterakun.updayes');

    });

    Route::middleware(['auth', 'checkRole:tu,pegawai'])->group(function () {
        Route::get("/absensi", [AbsensiController::class, 'index'])->name('absensi.index');
        Route::post("/absensi/lainnya", [AbsensiController::class, 'absenketeranganlainnya'])->name('absensi.absensilainnya');
        Route::post("/absensi", [AbsensiController::class, 'absensi'])->name('absensi.absen');
        Route::get("/laporankinerja/tambah", [LaporanKinerjaController::class, "create"])->name('laporankinerja.create');
        Route::get("/evaluation/all", [EvaluasiLaporanController::class, "index"])->name('evaluation.index');
        Route::get("/evaluation/detail/{id}", [EvaluasiLaporanController::class, "show"])->name('evaluation.detail');
        Route::post("/laporankinerja/tambah/push", [LaporanKinerjaController::class, "store"])->name('laporankinerja.store');
    });

    Route::middleware(['auth', 'checkRole:tu'])->group(function () {
        Route::get("/laporankinerja/v/q/all", [LaporanKinerjaController::class, "verifikasiLaporan"])->name('laporankinerja.verifikasi');
        Route::get("/laporankinerja/tu/all", [LaporanKinerjaController::class, "tuLaporan"])->name('laporankinerja.tuall');
        Route::post('/evaluation/store/{id}/id', [EvaluasiLaporanController::class, 'store'])->name('evaluation.store');

        Route::get("/pelatihan/all", [PelatihanPegawaiController::class, "index"])->name('pelatihan.index');
        Route::post('/pelatihan/store', [PelatihanPegawaiController::class, 'store'])->name('pelatihan.store');

        Route::post("/laporankinerja/cetak-seluruh/laporan", [LaporanKinerjaController::class, "exportAllLaporan"])->name('laporankinerja.cetakseluruh');
        Route::post("/laporankinerja/v/q/{id}/bypegawai/cetak", [LaporanKinerjaController::class, "exportAllLaporanById"])->name('laporankinerja.cetakbyid');
        Route::post("/laporankinerja/tambah/acc/{id}", [LaporanKinerjaController::class, "verifikasiAcc"])->name('laporankinerja.acc');
        Route::post("/laporankinerja/tambah/acc/all/tu", [LaporanKinerjaController::class, "verifikasiSemuaLaporanMenjadiAcc"])->name('laporankinerja.accAllByTu');
        Route::post("/laporankinerja/tambah/tolak/{id}", [LaporanKinerjaController::class, "verifikasiTolak"])->name('laporankinerja.tolak');
    });


    Route::middleware(['auth', 'checkRole:pegawai,admin'])->group(function () {
        Route::get("/laporankinerja/edit/{id}", [LaporanKinerjaController::class, "edit"])->name('laporankinerja.edit');
        Route::post("/laporankinerja/edit/{id}/update", [LaporanKinerjaController::class, "update"])->name('laporankinerja.update');
        Route::delete("/laporankinerja/item/{id}/hapus", [LaporanKinerjaController::class, "destroy"])->name('laporankinerja.hapus');
    });


    Route::middleware(['auth'])->group(function () {
        Route::get("/laporankinerja/all", [LaporanKinerjaController::class, "index"])->name('laporankinerja.index');
        Route::get("/laporankinerja/v/q/{id}/bypegawai", [LaporanKinerjaController::class, "getAllLaporanPegawaiById"])->name('laporankinerja.byidpegawai');
        Route::get("/pelatihan/all/semua/tu", [PelatihanPegawaiController::class, "getAllPelatihan"])->name('pelatihan.semua');

        Route::get("/", [DashboardController::class, 'index'])->name('dashboard');
        Route::get("/absensi/q", [AbsensiController::class, 'detailkehadiranSearch'])->name('absensi.search');
        Route::get("/absensi/kehadiran", [AbsensiController::class, 'detailkehadiran'])->name('absensi.detailkehadiran');

        Route::get("/pegawai/all", [PegawaiController::class, "index"])->name('pegawai.index');

    });











// Route::get('/register', [Authcontroller::class, 'register'])->name('register.index');
// Route::get('/resetpassword/halaman', [Authcontroller::class, 'resetPassword'])->name('resetpassword.index');
// Route::post('/resetpassword/posting', [Authcontroller::class, 'resetPasswordStore'])->name('resetpassword.store');
// Route::get('/register/{id}/addting', [Authcontroller::class, 'terimaPegawai'])->name('terima.store');
// Route::post('/register/add', [Authcontroller::class, 'createRegister'])->name('register.store');


// Route::post("/ubah/password/{email}", [AuthController::class, 'ubahpassword'])->name('ubah.password');
// Route::get("/reset/auth/{email}/{token}", [AuthController::class, 'confirmpassword'])->name('reset.pw');
