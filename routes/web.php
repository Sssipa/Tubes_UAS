<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\Admin\DosenController as AdminDosenController;
use App\Http\Controllers\Admin\MahasiswaController as AdminMahasiswaController;
use App\Http\Controllers\Admin\TahunAkademikController as AdminTahunAkademikController;
use App\Http\Controllers\Admin\MataKuliahController as AdminMataKuliahController;
use App\Http\Controllers\Admin\RuanganController as AdminRuanganController;
use App\Http\Controllers\Admin\JadwalKuliahController as AdminJadwalKuliahController;
use App\Http\Controllers\Mahasiswa\KrsController as MahasiswaKrsController;
use App\Http\Controllers\Mahasiswa\KhsController as MahasiswaKhsController;
use App\Http\Controllers\Mahasiswa\JadwalController as MahasiswaJadwalController;
use App\Http\Controllers\Dosen\JadwalController as DosenJadwalController;
use App\Http\Controllers\Dosen\InputNilaiController as DosenInputNilaiController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\KhsController as AdminKhsController;

use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('auth.authenticate');
Route::delete('/logout', [AuthController::class, 'logout'])->name('auth.logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/mahasiswa/dashboard', [MahasiswaController::class, 'index'])->name('mahasiswa.dashboard');
    Route::get('/dosen/dashboard', [DosenController::class, 'index'])->name('dosen.dashboard');
});

Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dosen', [AdminDosenController::class, 'index'])->name('admin.dosen.index');
    Route::get('/dosen/create', [AdminDosenController::class, 'create'])->name('admin.dosen.create');
    Route::post('/dosen', [AdminDosenController::class, 'store'])->name('admin.dosen.store');
    Route::get('/dosen/{id}', [AdminDosenController::class, 'show'])->name('admin.dosen.show');
    Route::get('/dosen/{id}/edit', [AdminDosenController::class, 'edit'])->name('admin.dosen.edit');
    Route::put('/dosen/{id}', [AdminDosenController::class, 'update'])->name('admin.dosen.update');
    Route::delete('/dosen/{id}', [AdminDosenController::class, 'destroy'])->name('admin.dosen.destroy');

    Route::get('/mahasiswa', [AdminMahasiswaController::class, 'index'])->name('admin.mahasiswa.index');
    Route::get('/mahasiswa/create', [AdminMahasiswaController::class, 'create'])->name('admin.mahasiswa.create');
    Route::post('/mahasiswa', [AdminMahasiswaController::class, 'store'])->name('admin.mahasiswa.store');
    Route::get('/mahasiswa/{id}', [AdminMahasiswaController::class, 'show'])->name('admin.mahasiswa.show');
    Route::get('/mahasiswa/{id}/edit', [AdminMahasiswaController::class, 'edit'])->name('admin.mahasiswa.edit');
    Route::put('/mahasiswa/{id}', [AdminMahasiswaController::class, 'update'])->name('admin.mahasiswa.update');
    Route::delete('/mahasiswa/{id}', [AdminMahasiswaController::class, 'destroy'])->name('admin.mahasiswa.destroy');

    Route::get('/tahun-akademik', [AdminTahunAkademikController::class, 'index'])->name('admin.tahun-akademik.index');
    Route::get('/tahun-akademik/create', [AdminTahunAkademikController::class, 'create'])->name('admin.tahun-akademik.create');
    Route::post('/tahun-akademik', [AdminTahunAkademikController::class, 'store'])->name('admin.tahun-akademik.store');
    Route::delete('/tahun-akademik/{id}', [AdminTahunAkademikController::class, 'destroy'])->name('admin.tahun-akademik.destroy');
    Route::patch('/tahun-akademik/{id}/set-aktif', [AdminTahunAkademikController::class, 'setAktif'])
        ->name('admin.tahun-akademik.setAktif');

    Route::get('/mata-kuliah', [AdminMataKuliahController::class, 'index'])->name('admin.mata-kuliah.index');
    Route::get('/mata-kuliah/create', [AdminMataKuliahController::class, 'create'])->name('admin.mata-kuliah.create');
    Route::post('/mata-kuliah', [AdminMataKuliahController::class, 'store'])->name('admin.mata-kuliah.store');
    Route::get('/mata-kuliah/{id}', [AdminMataKuliahController::class, 'show'])->name('admin.mata-kuliah.show');
    Route::get('/mata-kuliah/{id}/edit', [AdminMataKuliahController::class, 'edit'])->name('admin.mata-kuliah.edit');
    Route::put('/mata-kuliah/{id}', [AdminMataKuliahController::class, 'update'])->name('admin.mata-kuliah.update');
    Route::delete('/mata-kuliah/{id}', [AdminMataKuliahController::class, 'destroy'])->name('admin.mata-kuliah.destroy');

    Route::get('/ruangan', [AdminRuanganController::class, 'index'])->name('admin.ruangan.index');
    Route::get('/ruangan/create', [AdminRuanganController::class, 'create'])->name('admin.ruangan.create');
    Route::post('/ruangan', [AdminRuanganController::class, 'store'])->name('admin.ruangan.store');
    Route::get('/ruangan/{id}', [AdminRuanganController::class, 'show'])->name('admin.ruangan.show');
    Route::get('/ruangan/{id}/edit', [AdminRuanganController::class, 'edit'])->name('admin.ruangan.edit');
    Route::put('/ruangan/{id}', [AdminRuanganController::class, 'update'])->name('admin.ruangan.update');
    Route::delete('/ruangan/{id}', [AdminRuanganController::class, 'destroy'])->name('admin.ruangan.destroy');

    Route::resource('jadwal-kuliah', AdminJadwalKuliahController::class);

    Route::get('/user', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::get('/user/create', [AdminUserController::class, 'create'])->name('admin.users.create');
    Route::post('/user', [AdminUserController::class, 'store'])->name('admin.user.store');
    Route::get('/user/{id}', [AdminUserController::class, 'show'])->name('admin.user.show');
    Route::get('/user/{id}/edit', [AdminUserController::class, 'edit'])->name('admin.user.edit');
    Route::put('/user/{id}', [AdminUserController::class, 'update'])->name('admin.user.update');
    Route::delete('/user/{id}', [AdminUserController::class, 'destroy'])->name('admin.user.destroy');

    Route::get('/khs', [AdminKhsController::class, 'index'])->name('admin.khs.index');
    Route::get('/khs/create', [AdminKhsController::class, 'create'])->name('admin.khs.create');
    Route::post('/khs', [AdminKhsController::class, 'store'])->name('admin.khs.store');
    Route::get('/khs/{id}', [AdminKhsController::class, 'show'])->name('admin.khs.show');
    Route::get('/khs/{id}/edit', [AdminKhsController::class, 'edit'])->name('admin.khs.edit');
    Route::put('/khs/{id}', [AdminKhsController::class, 'update'])->name('admin.khs.update');
    Route::delete('/khs/{id}', [AdminKhsController::class, 'destroy'])->name('admin.khs.destroy');


});

Route::middleware(['auth', 'role:mahasiswa'])->group(function () {
    Route::get('mahasiswa/krs', [MahasiswaKrsController::class, 'createOrShow'])->name('mahasiswa.krs.createOrShow');
    Route::post('mahasiswa/krs', [MahasiswaKrsController::class, 'store'])->name('mahasiswa.krs.store');

    Route::get('mahasiswa/jadwal', [MahasiswaJadwalController::class, 'index'])->name('mahasiswa.jadwal.index');
    Route::get('mahasiswa/khs', [MahasiswaKhsController::class, 'index'])->name('mahasiswa.khs.index');
    Route::get('mahasiswa/khs/{semester}', [MahasiswaKhsController::class, 'show'])->name('mahasiswa.khs.show');
});

Route::middleware(['auth', 'role:dosen'])->group(function () {
    Route::get('/dosen/jadwal-mengajar', [DosenJadwalController::class, 'index'])->name('dosen.jadwal.index');
    Route::get('/dosen/input-nilai', [DosenInputNilaiController::class, 'index'])->name('dosen.input-nilai.index');
    Route::get('/dosen/input-nilai/kelas/{id_jadwal_kuliah}', [DosenInputNilaiController::class, 'showKelas'])->name('dosen.input-nilai.showKelas');
    Route::post('/dosen/input-nilai/kelas/{id_jadwal_kuliah}', [DosenInputNilaiController::class, 'storeNilai'])->name('dosen.input-nilai.store');
});

// Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
//     Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
//     // Tambahkan route admin lainnya di sini
// });

// Route::middleware(['auth', 'role:mahasiswa'])->group(function () {
//     Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
// });

// Route::get('/', function () {
//     return view('welcome');
// })->name('home');
