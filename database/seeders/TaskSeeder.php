<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\Validation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $si = Validation::create([
            'name'=>'si',
            'value'=>'si',
        ]);
        $no = Validation::create([
            'name'=>'no',
            'value'=>'no',
        ]);
        $numerico = Validation::create([
            'name'=>'Valor numérico',
            'value'=>'number',
        ]);
        $texto = Validation::create([
            'name'=>'Texto',
            'value'=>'text',
        ]);
        // revision ups
        $central = Task::create([
            'name'=>'Revision de UPS Central',
            'main'=>true,
        ]);
        $starlink = Task::create([
            'name'=>'Revision de UPS Starlinks',
            'main'=>true,
        ]);
        $digital = Task::create([
            'name'=>'Revision de UPS Encoder Digital',
            'main'=>true,
        ]);
        $internet = Task::create([
            'name'=>'Revision de UPS para internet',
            'main'=>true,
        ]);
        //subtareas ups
        $linea = Task::create([
            'name'=>'En Linea',
            'main'=>false,
        ]);
        $voltios = Task::create([
            'name'=>'Voltios',
            'main'=>false,
        ]);
        $amperes= Task::create([
            'name'=>'Amperes',
            'main'=>false,
        ]);
        $KVA= Task::create([
            'name'=>'KVA',
            'main'=>false,
        ]);
        $falla= Task::create([
            'name'=>'Falla',
            'main'=>false,
        ]);
        $bateria= Task::create([
            'name'=>'Bateria',
            'main'=>false,
        ]);
        //asignar validacions a tareas
        $linea->validations()->attach($si->id);
        $linea->validations()->attach($no->id);
        $voltios->validations()->attach($numerico->id);
        $amperes->validations()->attach($numerico->id);
        $KVA->validations()->attach($numerico->id);
        $falla->validations()->attach($si->id);
        $falla->validations()->attach($no->id);
        $bateria->validations()->attach($numerico->id);
        //asignar tareas principales a tareas secundarias
        $central->subtasks()->attach($linea->id);
        $central->subtasks()->attach($voltios->id);
        $central->subtasks()->attach($amperes->id);
        $central->subtasks()->attach($KVA->id);
        $central->subtasks()->attach($falla->id);

        $starlink->subtasks()->attach($linea->id);
        $starlink->subtasks()->attach($voltios->id);
        $starlink->subtasks()->attach($amperes->id);
        $starlink->subtasks()->attach($KVA->id);
        $starlink->subtasks()->attach($bateria->id);

        $digital->subtasks()->attach($linea->id);
        $digital->subtasks()->attach($voltios->id);
        $digital->subtasks()->attach($amperes->id);
        $digital->subtasks()->attach($KVA->id);
        $digital->subtasks()->attach($bateria->id);

        $internet->subtasks()->attach($linea->id);
        $internet->subtasks()->attach($voltios->id);
        $internet->subtasks()->attach($amperes->id);
        $internet->subtasks()->attach($KVA->id);
        $internet->subtasks()->attach($bateria->id);

        // aire acondicionado
        $aire = Task::create([
            'name'=>'Mantenimiento Aire acondicionado',
            'main'=>true,
        ]);
        //subtareas aire
        $encender12 = Task::create([
            'name'=> 'Encender a las 12 del dia',
            'main'=>false,
        ]);
        $apagar17 = Task::create([
            'name'=> 'Apagar a las 5 pm',
            'main'=>false,
        ]);
        $encender21 = Task::create([
            'name'=> 'Encender a las 21 horas',
            'main'=>false,
        ]);
        $apagar6 = Task::create([
            'name'=> 'Apagar a las 6:30 am',
            'main'=>false,
        ]);
        $fallaAire = Task::create([
            'name'=>'Falla en el Aire acondicionado',
            'main'=>false,
        ]);
        //asignar validaciones
        $encender12->validations()->attach($si->id);
        $encender12->validations()->attach($no->id);
        $apagar17->validations()->attach($si->id);
        $apagar17->validations()->attach($no->id);
        $encender21->validations()->attach($si->id);
        $encender21->validations()->attach($no->id);
        $apagar6->validations()->attach($si->id);
        $apagar6->validations()->attach($no->id);
        $fallaAire->validations()->attach($si->id);
        $fallaAire->validations()->attach($no->id);
        //asignar subtareas a tarea principal
        $aire->subtasks()->attach($encender12->id);
        $aire->subtasks()->attach($apagar17->id);
        $aire->subtasks()->attach($encender21->id);
        $aire->subtasks()->attach($apagar6->id);
        $aire->subtasks()->attach($fallaAire->id);

        //revision ccr
        $revisionCCR = Task::create([
            'name'=>'Revision de CCR',
            'main'=>true,
        ]);
        //subtareas ccr
        $encendidoCCR0 = Task::create([
            'name'=>'Revisar que el CCR0 este encendido.',
            'main'=>false,
        ]);
        $encendidoCCR1 = Task::create([
            'name'=>'Revisar que el CCR1 este encendido.',
            'main'=>false,
        ]);
        //asignar validaciones
        $encendidoCCR0->validations()->attach($si->id);
        $encendidoCCR0->validations()->attach($no->id);
        $encendidoCCR1->validations()->attach($si->id);
        $encendidoCCR1->validations()->attach($no->id);
        //asignar subtareas a tarea principal
        $revisionCCR->subtasks()->attach($encendidoCCR0->id);
        $revisionCCR->subtasks()->attach($encendidoCCR1->id);
    }
}
