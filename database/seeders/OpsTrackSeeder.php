<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\TaskCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class OpsTrackSeeder extends Seeder
{
    public function run(): void
    {
        $warehouse = Department::firstOrCreate(
            ['name' => 'Gudang'],
            ['description' => 'Pengelolaan stok dan barang']
        );

        $distribution = Department::firstOrCreate(
            ['name' => 'Distribusi'],
            ['description' => 'Pengiriman dan distribusi barang']
        );

        $administration = Department::firstOrCreate(
            ['name' => 'Administrasi'],
            ['description' => 'Administrasi dan pelaporan operasional']
        );

        foreach (
            [
                'Pengecekan Stok',
                'Pengiriman Barang',
                'Administrasi Dokumen',
                'Rekapitulasi Laporan',
                'Pemeliharaan Sistem',
            ] as $category
        ) {
            TaskCategory::firstOrCreate(
                ['name' => $category],
                ['is_active' => true]
            );
        }

        User::updateOrCreate(
            ['email' => 'admin@opstrack.test'],
            [
                'name' => 'Admin OpsTrack',
                'password' => Hash::make('Admin123!'),
                'role' => 'admin',
                'department_id' => null,
                'is_active' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'manager@opstrack.test'],
            [
                'name' => 'Manager Gudang',
                'password' => Hash::make('Manager123!'),
                'role' => 'manager',
                'department_id' => $warehouse->id,
                'is_active' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'staff@opstrack.test'],
            [
                'name' => 'Staff Gudang',
                'password' => Hash::make('Staff123!'),
                'role' => 'staff',
                'department_id' => $warehouse->id,
                'is_active' => true,
            ]
        );
    }
}
