<?php

use App\Http\Controllers\AlertMessageController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\FasilitasController;
use App\Http\Controllers\JadwalTidakTersediaController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\KetuaJurusanController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\Menu\MenuGroupController;
use App\Http\Controllers\Menu\MenuItemController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\OrganisasiIntraController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\RoleAndPermission\AssignPermissionController;
use App\Http\Controllers\RoleAndPermission\AssignUserToRoleController;
use App\Http\Controllers\RoleAndPermission\PermissionController;
use App\Http\Controllers\RoleAndPermission\RoleController;
use App\Http\Controllers\RuangController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\TipeDokumenPeminjamanController;
use App\Http\Controllers\UserController;
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

Route::get('/', function () {
    return view('auth.login');
});
Route::group(['middleware' => ['auth']], function () {
    Route::get('/dashboard', function () {
        return view('home');
    })->name('dashboard');


    Route::post('/message/read/{id}', [MessageController::class, 'markAsRead'])->name('message.read');
    Route::post('/alert/read/{id}', [MessageController::class, 'alertAsRead'])->name('alert.read');

    Route::prefix('master-management')->group(function () {
        //jurusan
        Route::resource('jurusan', JurusanController::class);
        Route::post('/jurusan/list', [JurusanController::class, 'list'])->name('jurusan.list');

        //prodi
        Route::resource('prodi', ProdiController::class);
        Route::post('/prodi/list', [ProdiController::class, 'list'])->name('prodi.list');

        // ketua-jurusan
        Route::resource('ketua-jurusan', KetuaJurusanController::class);
        Route::post('/ketua-jurusan/list', [KetuaJurusanController::class, 'list'])->name('ketua-jurusan.list');

        // organisasi
        Route::resource('organisasi', OrganisasiIntraController::class);
        Route::post('/organisasi/list', [OrganisasiIntraController::class, 'list'])->name('organisasi.list');

        // fasilitas
        Route::resource('fasilitas', FasilitasController::class);
        Route::post('/fasilitas/list', [FasilitasController::class, 'list'])->name('fasilitas.list');

        //ruang
        Route::resource('ruang', RuangController::class);
        Route::post('/ruang/list', [RuangController::class, 'list'])->name('ruang.list');

        //tipe-dokumen-peminjaman
        Route::resource('tipe-dokumen-peminjaman', TipeDokumenPeminjamanController::class);
        Route::post('/tipe-dokumen-peminjaman/list', [TipeDokumenPeminjamanController::class, 'list'])->name('tipe-dokumen-peminjaman.list');

        //jadwal-tidak-tersedia
        Route::resource('jadwal-tidak-tersedia', JadwalTidakTersediaController::class);
        Route::post('/jadwal-tidak-tersedia/list', [JadwalTidakTersediaController::class, 'list'])->name('jadwal-tidak-tersedia.list');
        Route::get('/jadwal-tidak-tersedia/get-ruang/{id}', [JadwalTidakTersediaController::class, 'getRuang'])->name('jadwal-tidak-tersedia.get-ruang');
    });

    Route::prefix('user-management')->group(function () {
        Route::resource('user', UserController::class);
        Route::post('/user/list', [UserController::class, 'list'])->name('user.list');

        //dosen
        Route::resource('dosen', DosenController::class);
        Route::post('/dosen/list', [DosenController::class, 'list'])->name('dosen.list');
        Route::get('/import-dosen', [DosenController::class, 'showImport'])->name('dosen.show-import');
        Route::post('/import-dosen', [DosenController::class, 'import'])->name('dosen.import');

        //staff
        Route::resource('staff', StaffController::class);
        Route::post('/staff/list', [StaffController::class, 'list'])->name('staff.list');

        //mahasiswa
        Route::resource('mahasiswa', MahasiswaController::class);
        Route::post('/mahasiswa/list', [MahasiswaController::class, 'list'])->name('mahasiswa.list');
        Route::get('/mahasiswa/getprodi/{id}', [MahasiswaController::class, 'getProdi'])->name('mahasiswa.getProdi');
    });
    Route::prefix('category-management')->group(function () {
        Route::resource('category', CategoryController::class);
    });

    Route::prefix('menu-management')->group(function () {
        Route::resource('menu-group', MenuGroupController::class);
        Route::post('/menu-group/list', [MenuGroupController::class, 'list'])->name('menu-group.list');

        Route::resource('menu-item', MenuItemController::class);
        Route::post('/menu-item/list', [MenuItemController::class, 'list'])->name('menu-item.list');
    });

    Route::group(['prefix' => 'role-and-permission'], function () {
        //role
        Route::resource('role', RoleController::class);
        Route::post('/role/list', [RoleController::class, 'list'])->name('role.list');

        //permission
        Route::resource('permission', PermissionController::class);
        Route::post('/permission/list', [PermissionController::class, 'list'])->name('permission.list');

        //assign permission
        Route::get('assign', [AssignPermissionController::class, 'index'])->name('assign.index');
        Route::get('assign/create', [AssignPermissionController::class, 'create'])->name('assign.create');
        Route::get('assign/{role}/edit', [AssignPermissionController::class, 'edit'])->name('assign.edit');
        Route::put('assign/{role}', [AssignPermissionController::class, 'update'])->name('assign.update');
        Route::post('assign', [AssignPermissionController::class, 'store'])->name('assign.store');
        Route::post('/assign/list', [AssignPermissionController::class, 'list'])->name('assign.list');

        //assign user to role
        Route::get('assign-user', [AssignUserToRoleController::class, 'index'])->name('assign.user.index');
        Route::get('assign-user/create', [AssignUserToRoleController::class, 'create'])->name('assign.user.create');
        Route::post('assign-user', [AssignUserToRoleController::class, 'store'])->name('assign.user.store');
        Route::get('assign-user/{user}/edit', [AssignUserToRoleController::class, 'edit'])->name('assign.user.edit');
        Route::put('assign-user/{user}', [AssignUserToRoleController::class, 'update'])->name('assign.user.update');
        Route::post('/assign-user/list', [AssignUserToRoleController::class, 'list'])->name('assign.user.list');
    });
});
