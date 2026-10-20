<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            [
                'name' => 'Gudang',
                'description' => 'Mengelola persediaan dan aktivitas pergudangan.',
                'is_active' => true,
            ],
            [
                'name' => 'Distribusi',
                'description' => 'Mengelola pengiriman dan distribusi barang.',
                'is_active' => true,
            ],
            [
                'name' => 'Administrasi',
                'description' => 'Mengelola dokumen dan laporan operasional.',
                'is_active' => true,
            ],
        ];

        foreach ($departments as $department) {
            Department::updateOrCreate(
                ['name' => $department['name']],
                $department
            );
        }
    }
}
