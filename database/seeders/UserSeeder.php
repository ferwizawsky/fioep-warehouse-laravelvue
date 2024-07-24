<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->detaildata() as $item) {
            $user = User::create([
                'name' => $item['name'],
                'email' => $item['email'],
                'username' => $item['username'],
                'password' => Hash::make($item['password']),
                'role_id' => $item['role_id']
            ]);
        }
    }
    public function detaildata()
    {
        return [
            [
                'name' => 'Udin Fatui',
                'email' => 'ferryspedia14@gmail.com',
                'username' => 'udin',
                'password' => 'udinfatui',
                'role_id' => 2
            ],
            [
                'name' => 'Admoon FIOEP',
                'email' => 'admoon@fioep.com',
                'username' => 'admoon',
                'password' => 'admoon2023',
                'role_id' => 2
            ],
        ];
    }
}
