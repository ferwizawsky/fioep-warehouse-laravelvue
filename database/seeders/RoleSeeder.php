<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            ['name' => 'manager_a', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'manager_b', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'staff_pembelian', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'staff_gudang', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'superadmin', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
