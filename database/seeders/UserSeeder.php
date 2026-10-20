<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = [
            'name' => 'Administrator OpsTrack',
            'email' => 'admin@opstrack.test',
            'role' => 'admin',
            'department_id' => null,
            'is_active' => true,
            'password' => 'Admin123!',
        ];

        User::updateOrCreate(
            ['email' => $admin['email']],
            $admin
        );

        $users = [
            [
                'name' => 'Manager Gudang',
                'email' => 'manager@opstrack.test',
                'role' => 'manager',
                'department' => 'Gudang',
            ],
            [
                'name' => 'Manager Distribusi',
                'email' => 'manager.distribusi@opstrack.test',
                'role' => 'manager',
                'department' => 'Distribusi',
            ],
            [
                'name' => 'Manager Administrasi',
                'email' => 'manager.administrasi@opstrack.test',
                'role' => 'manager',
                'department' => 'Administrasi',
            ],
            [
                'name' => 'Staff Gudang 1',
                'email' => 'staff@opstrack.test',
                'role' => 'staff',
                'department' => 'Gudang',
            ],
            [
                'name' => 'Staff Gudang 2',
                'email' => 'staff.gudang2@opstrack.test',
                'role' => 'staff',
                'department' => 'Gudang',
            ],
            [
                'name' => 'Staff Distribusi 1',
                'email' => 'staff.distribusi@opstrack.test',
                'role' => 'staff',
                'department' => 'Distribusi',
            ],
            [
                'name' => 'Staff Distribusi 2',
                'email' => 'staff.distribusi2@opstrack.test',
                'role' => 'staff',
                'department' => 'Distribusi',
            ],
            [
                'name' => 'Staff Administrasi 1',
                'email' => 'staff.administrasi@opstrack.test',
                'role' => 'staff',
                'department' => 'Administrasi',
            ],
            [
                'name' => 'Staff Administrasi 2',
                'email' => 'staff.administrasi2@opstrack.test',
                'role' => 'staff',
                'department' => 'Administrasi',
            ],
        ];

        foreach ($users as $data) {
            $department = Department::where(
                'name',
                $data['department']
            )->firstOrFail();

            User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'role' => $data['role'],
                    'department_id' => $department->id,
                    'is_active' => true,
                    'password' => $data['role'] === 'manager'
                        ? 'Manager123!'
                        : 'Staff123!',
                ]
            );
        }
    }
}
