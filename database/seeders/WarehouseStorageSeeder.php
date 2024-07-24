<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WarehouseStorageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('warehouse_storages')->insert([
            [
                'name' => 'Storage A',
                'description' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Storage B',
                'description' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Storage C',
                'description' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
