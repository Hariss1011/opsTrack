<?php

namespace Database\Seeders;

use App\Models\TaskCategory;
use Illuminate\Database\Seeder;

class TaskCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Pengecekan Stok',
                'description' => 'Pemeriksaan dan pencocokan stok barang.',
            ],
            [
                'name' => 'Pengiriman Barang',
                'description' => 'Aktivitas persiapan dan pengiriman barang.',
            ],
            [
                'name' => 'Administrasi Dokumen',
                'description' => 'Pengelolaan dokumen operasional.',
            ],
            [
                'name' => 'Rekapitulasi Laporan',
                'description' => 'Penyusunan dan rekapitulasi laporan.',
            ],
            [
                'name' => 'Pemeliharaan Sistem',
                'description' => 'Pemeliharaan sistem dan dokumentasi teknis.',
            ],
            [
                'name' => 'Penerimaan Barang',
                'description' => 'Pencatatan barang yang masuk ke gudang.',
            ],
            [
                'name' => 'Penataan Gudang',
                'description' => 'Penataan lokasi penyimpanan barang.',
            ],
            [
                'name' => 'Evaluasi Operasional',
                'description' => 'Evaluasi proses dan kinerja operasional.',
            ],
        ];

        foreach ($categories as $category) {
            TaskCategory::updateOrCreate(
                ['name' => $category['name']],
                array_merge($category, ['is_active' => true])
            );
        }
    }
}
