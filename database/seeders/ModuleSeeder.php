<?php

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Module::create([
            'name'=>'Usuarios',
            'url'=>'users',
            'description'=>'Módulo para la gestion de usuarios'
        ]);
        Module::create([
            'name'=>'Roles',
            'url'=>'roles',
            'description'=>'Módulo para la gestion de roles'
        ]);
        Module::create([
            'name'=>'Modulos',
            'url'=>'modules',
            'description'=>'Módulo para la gestion de módulos'
        ]);
        Module::create([
            'name'=>'Bitacora',
            'url'=>'reviews',
            'description'=>'Módulo para la gestion de la bitácora'
        ]);
        Module::create([
            'name'=>'Tareas',
            'url'=>'tasks',
            'description'=>'Módulo para la gestion de tareas'
        ]);
        Module::create([
            'name'=>'Sucursales',
            'url'=>'locations',
            'description'=>'Módulo para la gestion de sucursales'
        ]);
        Module::create([
            'name'=>'Validaciones',
            'url'=>'validations',
            'description'=>'Módulo para la gestion de validaciones'
        ]);
            Module::create([
            'name'=>'Dashboard',
            'url'=>'home',
            'description'=>'Módulo de dashboard'
        ]);
    }
}
