<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        tap(
            Role::create(['slug' => 'admin', 'name' => 'Administrator']), function ($role) {
                $role->modules()->sync(Module::all()->pluck('id')->toArray());
            }
        );
        tap(
            Role::create(['slug' => 'user', 'name' => 'User']),
            function($role){
                $role->modules()->sync(Module::whereIn('url',['review','home'])->pluck('id')->toArray());

            }
        );
    }
}
