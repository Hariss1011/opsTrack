<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            DepartmentSeeder::class,
            TaskCategorySeeder::class,
            UserSeeder::class,
            TaskSeeder::class,
            TaskStatusHistorySeeder::class,
            TaskUpdateSeeder::class,
        ]);
    }
}
