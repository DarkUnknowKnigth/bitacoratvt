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
            'description'=>'Módulo para la gestión de usuarios'
        ]);
        Module::create([
            'name'=>'Roles',
            'url'=>'roles',
            'description'=>'Módulo para la gestión de roles'
        ]);
        Module::create([
            'name'=>'Modulos',
            'url'=>'modules',
            'description'=>'Módulo para la gestión de módulos'
        ]);
        Module::create([
            'name'=>'Historial de bitácoras',
            'url'=>'reviews',
            'description'=>'Módulo para la gestión de la bitácora'
        ]);
        Module::create([
            'name'=>'Tareas',
            'url'=>'tasks',
            'description'=>'Módulo para la gestión de tareas'
        ]);
        Module::create([
            'name'=>'Sucursales',
            'url'=>'locations',
            'description'=>'Módulo para la gestión de sucursales'
        ]);
        Module::create([
            'name'=>'Validaciones',
            'url'=>'validations',
            'description'=>'Módulo para la gestión de validaciones'
        ]);
        Module::create([
            'name'=>'Captura',
            'url'=>'home',
            'description'=>'Módulo de captura de bitacoras'
        ]);
        Module::create([
            'name'=>'Bitácora',
            'url'=>'viewer',
            'description'=>'Módulo de visualización de bitácoras'
        ]);
        Module::create([
            'name'=>'Fallas',
            'url'=>'failures',
            'description'=>'Módulo de gestión de fallas'
        ]);
    }
}
