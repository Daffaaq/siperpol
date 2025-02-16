<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'dashboard']);
        Permission::create(['name' => 'master.management']);
        Permission::create(['name' => 'peminjaman.management']);
        Permission::create(['name' => 'user.management']);
        Permission::create(['name' => 'role.permission.management']);
        Permission::create(['name' => 'menu.management']);
        //user
        Permission::create(['name' => 'user.index']);
        Permission::create(['name' => 'user.create']);
        Permission::create(['name' => 'user.edit']);
        Permission::create(['name' => 'user.destroy']);
        Permission::create(['name' => 'user.import']);
        Permission::create(['name' => 'user.export']);

        //role
        Permission::create(['name' => 'role.index']);
        Permission::create(['name' => 'role.create']);
        Permission::create(['name' => 'role.edit']);
        Permission::create(['name' => 'role.destroy']);
        Permission::create(['name' => 'role.import']);
        Permission::create(['name' => 'role.export']);

        //permission
        Permission::create(['name' => 'permission.index']);
        Permission::create(['name' => 'permission.create']);
        Permission::create(['name' => 'permission.edit']);
        Permission::create(['name' => 'permission.destroy']);
        Permission::create(['name' => 'permission.import']);
        Permission::create(['name' => 'permission.export']);

        //assignpermission
        Permission::create(['name' => 'assign.index']);
        Permission::create(['name' => 'assign.create']);
        Permission::create(['name' => 'assign.edit']);
        Permission::create(['name' => 'assign.destroy']);

        //assingusertorole
        Permission::create(['name' => 'assign.user.index']);
        Permission::create(['name' => 'assign.user.create']);
        Permission::create(['name' => 'assign.user.edit']);

        //menu group 
        Permission::create(['name' => 'menu-group.index']);
        Permission::create(['name' => 'menu-group.create']);
        Permission::create(['name' => 'menu-group.edit']);
        Permission::create(['name' => 'menu-group.destroy']);

        //menu item 
        Permission::create(['name' => 'menu-item.index']);
        Permission::create(['name' => 'menu-item.create']);
        Permission::create(['name' => 'menu-item.edit']);
        Permission::create(['name' => 'menu-item.destroy']);

        //jurusan
        Permission::create(['name' => 'jurusan.index']);
        Permission::create(['name' => 'jurusan.create']);
        Permission::create(['name' => 'jurusan.edit']);
        Permission::create(['name' => 'jurusan.destroy']);

        //prodi
        Permission::create(['name' => 'prodi.index']);
        Permission::create(['name' => 'prodi.create']);
        Permission::create(['name' => 'prodi.edit']);
        Permission::create(['name' => 'prodi.destroy']);

        // Kepala Jurusan
        Permission::create(['name' => 'kepala-jurusan.index']);
        Permission::create(['name' => 'kepala-jurusan.create']);
        Permission::create(['name' => 'kepala-jurusan.edit']);
        Permission::create(['name' => 'kepala-jurusan.destroy']);

        // Organisasi
        Permission::create(['name' => 'organisasi.index']);
        Permission::create(['name' => 'organisasi.create']);
        Permission::create(['name' => 'organisasi.edit']);
        Permission::create(['name' => 'organisasi.destroy']);

        // Fasilitas
        Permission::create(['name' => 'fasilitas.index']);
        Permission::create(['name' => 'fasilitas.create']);
        Permission::create(['name' => 'fasilitas.edit']);
        Permission::create(['name' => 'fasilitas.destroy']);

        // ruang
        Permission::create(['name' => 'ruang.index']);
        Permission::create(['name' => 'ruang.create']);
        Permission::create(['name' => 'ruang.edit']);
        Permission::create(['name' => 'ruang.destroy']);

        // tipe-dokumen-peminjaman
        Permission::create(['name' => 'tipe-dokumen-peminjaman.index']);
        Permission::create(['name' => 'tipe-dokumen-peminjaman.create']);
        Permission::create(['name' => 'tipe-dokumen-peminjaman.edit']);
        Permission::create(['name' => 'tipe-dokumen-peminjaman.destroy']);

        // jadwal-tidak-tersedia
        Permission::create(['name' => 'jadwal-tidak-tersedia.index']);
        Permission::create(['name' => 'jadwal-tidak-tersedia.create']);
        Permission::create(['name' => 'jadwal-tidak-tersedia.edit']);
        Permission::create(['name' => 'jadwal-tidak-tersedia.destroy']);

        //peminjaman-ruang
        Permission::create(['name' => 'peminjaman-ruang.index']);
        Permission::create(['name' => 'peminjaman-ruang.create']);
        Permission::create(['name' => 'peminjaman-ruang.edit']);
        Permission::create(['name' => 'peminjaman-ruang.destroy']);

        //Dosen
        Permission::create(['name' => 'dosen.index']);
        Permission::create(['name' => 'dosen.create']);
        Permission::create(['name' => 'dosen.edit']);
        Permission::create(['name' => 'dosen.destroy']);

        //Staff
        Permission::create(['name' => 'staff.index']);
        Permission::create(['name' => 'staff.create']);
        Permission::create(['name' => 'staff.edit']);
        Permission::create(['name' => 'staff.destroy']);

        //Mahasiswa
        Permission::create(['name' => 'mahasiswa.index']);
        Permission::create(['name' => 'mahasiswa.create']);
        Permission::create(['name' => 'mahasiswa.edit']);
        Permission::create(['name' => 'mahasiswa.destroy']);

        // create roles 
        $roleUser = Role::create(['name' => 'admin']);
        $roleUser->givePermissionTo([
            'dashboard',
            'user.management',
            'user.index',
        ]);

        // create Super Admin
        $role = Role::create(['name' => 'super-admin']);
        $role->givePermissionTo(Permission::all());

        // create staff
        $role = Role::create(['name' => 'staff']);

        // create dosen
        $role = Role::create(['name' => 'dosen']);

        // create mahasiswa
        $role = Role::create(['name' => 'mahasiswa']);

        $role = Role::create(['name' => 'Kajur']);

        $role = Role::create(['name' => 'Bem']);
        
        $role = Role::create(['name' => 'Himpunan']);

        //assign user id 1 ke super admin
        $user = User::find(1);
        $user->assignRole('super-admin');
        $user = User::find(2);
        $user->assignRole('admin');
    }
}
