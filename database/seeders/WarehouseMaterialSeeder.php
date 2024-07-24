<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WarehouseMaterialSeeder extends Seeder
{

    private function generateUniqueCode()
    {
        do {
            // Generate a random length between 6 and 12
            $length = rand(6, 12);
            // Generate a random code of the specified length
            $code = Str::upper(Str::random($length));
        } while (DB::table('warehouse_materials')->where('generated_code', $code)->exists());

        return $code;
    }

    public function run()
    {
        $materials = DB::table('materials')->pluck('id');
        $warehouses = DB::table('warehouses')->pluck('id');

        DB::table('warehouse_materials')->insert([
            [
                'generated_code' => $this->generateUniqueCode(),
                'material_id' => $materials->random(),
                'warehouse_id' => $warehouses->random(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'generated_code' => $this->generateUniqueCode(),
                'material_id' => $materials->random(),
                'warehouse_id' => $warehouses->random(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'generated_code' => $this->generateUniqueCode(),
                'material_id' => $materials->random(),
                'warehouse_id' => $warehouses->random(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
