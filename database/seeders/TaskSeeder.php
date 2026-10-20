<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Task;
use App\Models\TaskCategory;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $tasks = [
            [
                'title' => 'Pengecekan Stok Barang Mingguan',
                'department' => 'Gudang',
                'category' => 'Pengecekan Stok',
                'manager' => 'manager@opstrack.test',
                'staff' => 'staff@opstrack.test',
                'priority' => 'high',
                'status' => 'pending_review',
                'days_due' => 2,
            ],
            [
                'title' => 'Penataan Rak Barang',
                'department' => 'Gudang',
                'category' => 'Penataan Gudang',
                'manager' => 'manager@opstrack.test',
                'staff' => 'staff.gudang2@opstrack.test',
                'priority' => 'medium',
                'status' => 'in_progress',
                'days_due' => 4,
            ],
            [
                'title' => 'Pencatatan Barang Masuk',
                'department' => 'Gudang',
                'category' => 'Penerimaan Barang',
                'manager' => 'manager@opstrack.test',
                'staff' => 'staff@opstrack.test',
                'priority' => 'urgent',
                'status' => 'blocked',
                'days_due' => 1,
            ],
            [
                'title' => 'Pemeriksaan Dokumen Pengiriman',
                'department' => 'Distribusi',
                'category' => 'Administrasi Dokumen',
                'manager' => 'manager.distribusi@opstrack.test',
                'staff' => 'staff.distribusi@opstrack.test',
                'priority' => 'high',
                'status' => 'pending_review',
                'days_due' => 2,
            ],
            [
                'title' => 'Rekapitulasi Pengiriman Mingguan',
                'department' => 'Distribusi',
                'category' => 'Rekapitulasi Laporan',
                'manager' => 'manager.distribusi@opstrack.test',
                'staff' => 'staff.distribusi2@opstrack.test',
                'priority' => 'medium',
                'status' => 'open',
                'days_due' => 5,
            ],
            [
                'title' => 'Evaluasi Ketepatan Pengiriman',
                'department' => 'Distribusi',
                'category' => 'Evaluasi Operasional',
                'manager' => 'manager.distribusi@opstrack.test',
                'staff' => 'staff.distribusi@opstrack.test',
                'priority' => 'low',
                'status' => 'completed',
                'days_due' => -2,
            ],
            [
                'title' => 'Pemeriksaan Dokumen Operasional',
                'department' => 'Administrasi',
                'category' => 'Administrasi Dokumen',
                'manager' => 'manager.administrasi@opstrack.test',
                'staff' => 'staff.administrasi@opstrack.test',
                'priority' => 'medium',
                'status' => 'pending_review',
                'days_due' => 3,
            ],
            [
                'title' => 'Penyusunan Laporan Bulanan',
                'department' => 'Administrasi',
                'category' => 'Rekapitulasi Laporan',
                'manager' => 'manager.administrasi@opstrack.test',
                'staff' => 'staff.administrasi2@opstrack.test',
                'priority' => 'high',
                'status' => 'in_progress',
                'days_due' => 5,
            ],
            [
                'title' => 'Pembaruan Dokumentasi Sistem',
                'department' => 'Administrasi',
                'category' => 'Pemeliharaan Sistem',
                'manager' => 'manager.administrasi@opstrack.test',
                'staff' => 'staff.administrasi@opstrack.test',
                'priority' => 'low',
                'status' => 'cancelled',
                'days_due' => 2,
            ],
        ];

        foreach ($tasks as $data) {
            $department = Department::where(
                'name',
                $data['department']
            )->firstOrFail();

            $category = TaskCategory::where(
                'name',
                $data['category']
            )->firstOrFail();

            $manager = User::where(
                'email',
                $data['manager']
            )->firstOrFail();

            $staff = User::where(
                'email',
                $data['staff']
            )->firstOrFail();

            $completedAt = $data['status'] === 'completed'
                ? now()->subDays(1)
                : null;

            Task::updateOrCreate(
                [
                    'title' => $data['title'],
                    'department_id' => $department->id,
                ],
                [
                    'description' => 'Tugas demo OpsTrack untuk '
                        . strtolower($data['department'])
                        . '. Lakukan pekerjaan sesuai prosedur dan '
                        . 'catat perkembangannya melalui sistem.',
                    'category_id' => $category->id,
                    'created_by' => $manager->id,
                    'assigned_to' => $staff->id,
                    'priority' => $data['priority'],
                    'status' => $data['status'],
                    'start_date' => now()->subDays(3)->toDateString(),
                    'due_date' => now()
                        ->addDays($data['days_due'])
                        ->toDateString(),
                    'completed_at' => $completedAt,
                ]
            );
        }
    }
}
