<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('materials')->insert([
            [
                'name' => 'Material A',
                'code' => 'MAT001',
                'img' => 'https://resume-beta.fioep.com/profile.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Material B',
                'code' => 'MAT002',
                'img' => 'https://fioep.com/img/bot1-min.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Material C',
                'code' => 'MAT003',
                'img' => 'https://resume-beta.fioep.com/profile.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
