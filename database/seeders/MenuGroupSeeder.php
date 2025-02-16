<?php

namespace Database\Seeders;

use App\Models\MenuGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MenuGroup::insert(
            [
                [
                    'name' => 'Dashboard',
                    'icon' => 'fas fa-tachometer-alt',
                    'permission_name' => 'dashboard',
                ],
                [
                    'name' => 'Master Management', // Menambahkan Master Management setelah Dashboard
                    'icon' => 'fas fa-briefcase', // Icon untuk Master Management
                    'permission_name' => 'master.management', // Permission untuk Master Management
                ],
                [
                    'name' => 'Rent Management', // Peminjaman Management yang baru
                    'icon' => 'fas fa-hand-holding-usd', // Icon yang lebih baik untuk Peminjaman Management
                    'permission_name' => 'peminjaman.management', // Permission untuk Peminjaman Management
                ],
                [
                    'name' => 'Users Management',
                    'icon' => 'fas fa-users',
                    'permission_name' => 'user.management',
                ],
                [
                    'name' => 'Role Management',
                    'icon' => 'fas fa-user-tag',
                    'permisison_name' => 'role.permission.management',
                ],
                [
                    'name' => 'Menu Management',
                    'icon' => 'fas fa-bars',
                    'permisison_name' => 'menu.management',
                ]
            ]
        );
    }
}
