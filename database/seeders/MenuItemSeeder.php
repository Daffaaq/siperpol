<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MenuItem::insert(
            [
                [
                    'name' => 'Dashboard',
                    'route' => 'dashboard',
                    'permission_name' => 'dashboard',
                    'menu_group_id' => 1,
                ],
                [
                    'name' => 'Jurusan',
                    'route' => 'master-management/jurusan',
                    'permission_name' => 'jurusan.index',
                    'menu_group_id' => 2,
                ],
                [
                    'name' => 'Prodi',
                    'route' => 'master-management/prodi',
                    'permission_name' => 'prodi.index',
                    'menu_group_id' => 2,
                ],
                [
                    'name' => 'Ketua Jurusan',
                    'route' => 'master-management/ketua-jurusan',
                    'permission_name' => 'ketua-jurusan.index',
                    'menu_group_id' => 2,
                ],
                [
                    'name' => 'Organisasi',
                    'route' => 'master-management/organisasi',
                    'permission_name' => 'organisasi.index',
                    'menu_group_id' => 2,
                ],
                [
                    'name' => 'Fasilitas',
                    'route' => 'master-management/fasilitas',
                    'permission_name' => 'fasilitas.index',
                    'menu_group_id' => 2,
                ],
                [
                    'name' => 'Ruang',
                    'route' => 'master-management/ruang',
                    'permission_name' => 'ruang.index',
                    'menu_group_id' => 2,
                ],
                [
                    'name' => 'Tipe Dokumen',
                    'route' => 'master-management/tipe-dokumen-peminjaman',
                    'permission_name' => 'tipe-dokumen-peminjaman.index',
                    'menu_group_id' => 2,
                ],
                [
                    'name' => 'Jadwal Tidak Tersedia',
                    'route' => 'master-management/jadwal-tidak-tersedia',
                    'permission_name' => 'jadwal-tidak-tersedia.index',
                    'menu_group_id' => 2,
                ],
                // Menu Group 3 (Peminjaman Management)
                [
                    'name' => 'Peminjaman Ruang',
                    'route' => 'peminjaman-management/peminjaman-ruang',
                    'permission_name' => 'peminjaman-ruang.index',
                    'menu_group_id' => 3,
                ],
                [
                    'name' => 'Dosen List',
                    'route' => 'user-management/dosen',
                    'permission_name' => 'dosen.index',
                    'menu_group_id' => 4,
                ],
                [
                    'name' => 'Staff List',
                    'route' => 'user-management/staff',
                    'permission_name' => 'staff.index',
                    'menu_group_id' => 4,
                ],
                [
                    'name' => 'Mahasiswa List',
                    'route' => 'user-management/mahasiswa',
                    'permission_name' => 'mahasiswa.index',
                    'menu_group_id' => 4,
                ],
                [
                    'name' => 'User List',
                    'route' => 'user-management/user',
                    'permission_name' => 'user.index',
                    'menu_group_id' => 4,
                ],
                [
                    'name' => 'Role List',
                    'route' => 'role-and-permission/role',
                    'permission_name' => 'role.index',
                    'menu_group_id' => 5,
                ],
                [
                    'name' => 'Permission List',
                    'route' => 'role-and-permission/permission',
                    'permission_name' => 'permission.index',
                    'menu_group_id' => 5,
                ],
                [
                    'name' => 'Permission To Role',
                    'route' => 'role-and-permission/assign',
                    'permission_name' => 'assign.index',
                    'menu_group_id' => 5,
                ],
                [
                    'name' => 'User To Role',
                    'route' => 'role-and-permission/assign-user',
                    'permission_name' => 'assign.user.index',
                    'menu_group_id' => 5,
                ],
                [
                    'name' => 'Menu Group',
                    'route' => 'menu-management/menu-group',
                    'permission_name' => 'menu-group.index',
                    'menu_group_id' => 6,
                ],
                [
                    'name' => 'Menu Item',
                    'route' => 'menu-management/menu-item',
                    'permission_name' => 'menu-item.index',
                    'menu_group_id' => 6,
                ],
            ]
        );
    }
}
