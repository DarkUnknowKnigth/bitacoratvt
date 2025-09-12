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
        User::create([
            'name'=>'administrador',
            'email'=>'admin@tuvision.mx',
            'password'=>Hash::make('admin2025'),
            'location_id'=>1,
            'role_id'=>1
        ]);
    }
}
