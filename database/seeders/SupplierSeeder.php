<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('suppliers')->insert([
            [
                'name' => 'Supplier A',
                'description' => 'Address A, Phone: 123456789',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Supplier B',
                'description' => 'Address B, Phone: 987654321',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Supplier C',
                'description' => 'Address C, Phone: 555666777',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
