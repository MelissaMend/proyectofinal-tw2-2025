<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminTipoId = DB::table('tipos')->where('tipo', '=', 'admin')->first()->id;

        DB::table('users')->insert([
            'tipos_id' => $adminTipoId,
            'username' => 'admin',
            'email' => 'admin@sistema.com',
            'password' => Hash::make('admin123'),
            'remember_token' => '',
        ]);
    }
}
