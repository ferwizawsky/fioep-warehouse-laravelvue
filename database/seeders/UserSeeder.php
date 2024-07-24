<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = DB::table('roles')->pluck('id', 'name');

        DB::table('users')->insert([
            [
                'name' => 'Superadmin',
                'username' => 'superadmin',
                'email' => 'superadmin@fioep.com',
                'password' => Hash::make('admoon48'),
                'role_id' => $roles['superadmin'],
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Manager A',
                'username' => 'manager_a',
                'email' => 'manager_a@fioep.com',
                'password' => Hash::make('password'),
                'role_id' => $roles['manager_a'],
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Manager B',
                'username' => 'manager_b',
                'email' => 'manager_b@fioep.com',
                'password' => Hash::make('password'),
                'role_id' => $roles['manager_b'],
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Staff Pembelian 1',
                'username' => 'staff_pembelian1',
                'email' => 'staff_pembelian1@fioep.com',
                'password' => Hash::make('password'),
                'role_id' => $roles['staff_pembelian'],
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Staff Pembelian 2',
                'username' => 'staff_pembelian2',
                'email' => 'staff_pembelian2@fioep.com',
                'password' => Hash::make('password'),
                'role_id' => $roles['staff_pembelian'],
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Staff Gudang 1',
                'username' => 'staff_gudang1',
                'email' => 'staff_gudang1@fioep.com',
                'password' => Hash::make('password'),
                'role_id' => $roles['staff_gudang'],
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Staff Gudang 2',
                'username' => 'staff_gudang2',
                'email' => 'staff_gudang2@fioep.com',
                'password' => Hash::make('password'),
                'role_id' => $roles['staff_gudang'],
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
    // public function detaildata()
    // {
    //     return [
    //         [
    //             'name' => 'Udin Fatui',
    //             'email' => 'ferryspedia14@gmail.com',
    //             'username' => 'udin',
    //             'password' => 'udinfatui',
    //             'role_id' => 2
    //         ],
    //         [
    //             'name' => 'Admoon FIOEP',
    //             'email' => 'admoon@fioep.com',
    //             'username' => 'admoon',
    //             'password' => 'admoon2023',
    //             'role_id' => 2
    //         ],
    //     ];
    // }
}
