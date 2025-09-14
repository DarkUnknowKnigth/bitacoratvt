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

        // Revision de EDFA - PON EDFA y 1550
        $revisionEDFA = Task::create([
            'name' => 'Revision de EDFA - PON EDFA y 1550',
            'main' => true,
        ]);

        // Subtareas EDFA
        $potenciaEntrada = Task::create(['name' => 'Potencia Entrada', 'main' => false]);
        $potenciaSalida = Task::create(['name' => 'Potencia de Salida', 'main' => false]);

        $potenciaEntrada->validations()->attach($numerico->id);
        $potenciaSalida->validations()->attach($numerico->id);

        $transmisor1550 = Task::create(['name' => 'Transmisor 1550', 'main' => false]);
        $edfa = Task::create(['name' => 'EDFA', 'main' => false]);
        $ponEdfa = Task::create(['name' => 'PON EDFA', 'main' => false]);

        $transmisor1550->validations()->attach($si->id);
        $transmisor1550->validations()->attach($no->id);
        $edfa->validations()->attach($si->id);
        $edfa->validations()->attach($no->id);
        $ponEdfa->validations()->attach($si->id);
        $ponEdfa->validations()->attach($no->id);

        $revisionEDFA->subtasks()->attach([
            $transmisor1550->id, $potenciaEntrada->id, $potenciaSalida->id,
            $edfa->id, $ponEdfa->id
        ]);

        // Revision de OLT
        $revisionOLT = Task::create(['name' => 'Revision de OLT', 'main' => true]);
        $oltEnLinea = Task::create(['name' => 'OLT en Linea', 'main' => false]);
        $oltEnLinea->validations()->attach([$si->id, $no->id]);
        $revisionOLT->subtasks()->attach($oltEnLinea->id);

        $ledsPON = Task::create(['name' => 'LEDS de PON de la OLT Encendidos', 'main' => true]);
        for ($i = 1; $i <= 16; $i++) {
            $pon = Task::create(['name' => "PON {$i} Funcionando", 'main' => false]);
            $pon->validations()->attach([$si->id, $no->id]);
            $ledsPON->subtasks()->attach($pon->id);
        }

        // Revision de Servicio STARLINK / WINBOX
        $revisionStarlink = Task::create(['name' => 'Revision de Servicio STARLINK / WINBOX', 'main' => true]);
        $redesAbiertas = Task::create(['name' => 'Hay redes Starlink Abiertas', 'main' => false]);
        $redesAbiertas->validations()->attach([$si->id, $no->id]);
        $revisionStarlink->subtasks()->attach($redesAbiertas->id);

        for ($i = 1; $i <= 18; $i++) {
            $starlinkOp = Task::create(['name' => "Operando el Starlink {$i}", 'main' => false]);
            $starlinkOp->validations()->attach([$si->id, $no->id]);
            $revisionStarlink->subtasks()->attach($starlinkOp->id);
        }
        $fallaStarlink = Task::create(['name' => 'Falla en algun equipo Starlink', 'main' => false]);
        $fallaStarlink->validations()->attach([$si->id, $no->id]);
        $revisionStarlink->subtasks()->attach($fallaStarlink->id);

        // Revision de Transmisores
        $revisionTransmisores = Task::create(['name' => 'Revision de Transmisores', 'main' => true]);
        $transmisorZAE = Task::create(['name' => 'Revisar que el Transmisor 1310 de ZAE este prendido', 'main' => false]);
        $transmisorIPA = Task::create(['name' => 'Revisar que el Transmisor 1310 de IPA este prendido', 'main' => false]);
        $transmisorZOCRIN = Task::create(['name' => 'Revisar que el Transmisor 1310 de ZOC/RIN este prendido', 'main' => false]);

        $transmisorZAE->validations()->attach([$si->id, $no->id]);
        $transmisorIPA->validations()->attach([$si->id, $no->id]);
        $transmisorZOCRIN->validations()->attach([$si->id, $no->id]);

        $revisionTransmisores->subtasks()->attach([$transmisorZAE->id, $transmisorIPA->id, $transmisorZOCRIN->id]);

        // Revision de Encoder digitales
        $revisionEncoders = Task::create(['name' => 'Revision de Encoder digitales', 'main' => true]);
        for ($i = 1; $i <= 11; $i++) {
            $encoder = Task::create(['name' => "Encoder {$i}", 'main' => false]);
            $encoder->validations()->attach([$si->id, $no->id]);
            $revisionEncoders->subtasks()->attach($encoder->id);
        }

        // Revision de Canales
        $revisionCanales = Task::create(['name' => 'Revision de Canales', 'main' => true]);
        $fallaCanal = Task::create(['name' => 'Falla en algun canal', 'main' => false]);
        $fallaCanal->validations()->attach([$si->id, $no->id]);
        $revisionCanales->subtasks()->attach($fallaCanal->id);

        // Balanceo de Canales
        $balanceoCanales = Task::create(['name' => 'Balanceo de Canales', 'main' => true]);
        $balanceoAnalogos = Task::create(['name' => 'Balanceo Analogos', 'main' => false]);
        $balanceoDigitales = Task::create(['name' => 'Balanceo Digitales', 'main' => false]);

        $balanceoAnalogos->validations()->attach([$si->id, $no->id]);
        $balanceoDigitales->validations()->attach([$si->id, $no->id]);

        $balanceoCanales->subtasks()->attach([$balanceoAnalogos->id, $balanceoDigitales->id]);

        // Prender Ventiladores
        $prenderVentiladores = Task::create(['name' => 'Prender Ventiladores', 'main' => true]);
        $ventiladoresDigitales = Task::create(['name' => 'Para digitales', 'main' => false]);
        $ventiladoresOLT = Task::create(['name' => 'Para OLT', 'main' => false]);

        $ventiladoresDigitales->validations()->attach([$si->id, $no->id]);
        $ventiladoresOLT->validations()->attach([$si->id, $no->id]);

        $prenderVentiladores->subtasks()->attach([$ventiladoresDigitales->id, $ventiladoresOLT->id]);

        // Corte de Fibra Optica
        $corteFibra = Task::create(['name' => 'Corte de Fibra Optica', 'main' => true]);
        $huboCorte = Task::create(['name' => 'Hubo corte', 'main' => false]);
        $poblacionCorte = Task::create(['name' => 'A que poblacion', 'main' => false]);
        $distanciaCorte = Task::create(['name' => 'Distancia del Corte', 'main' => false]);
        $guardoTrazas = Task::create(['name' => 'Guardaste trazas', 'main' => false]);

        $huboCorte->validations()->attach([$si->id, $no->id]);
        $poblacionCorte->validations()->attach($texto->id);
        $distanciaCorte->validations()->attach($numerico->id);
        $guardoTrazas->validations()->attach([$si->id, $no->id]);

        $corteFibra->subtasks()->attach([$huboCorte->id, $poblacionCorte->id, $distanciaCorte->id, $guardoTrazas->id]);

        // Limpieza de CTC
        $limpiezaCTC = Task::create(['name' => 'Limpieza de CTC', 'main' => true]);
        for ($i = 1; $i <= 7; $i++) {
            $rack = Task::create(['name' => "Rack {$i}", 'main' => false]);
            $rack->validations()->attach([$si->id, $no->id]);
            $limpiezaCTC->subtasks()->attach($rack->id);
        }

        // Mantenimiento y Generales
        $mantenimientoGenerales = Task::create(['name' => 'Mantenimiento y Generales', 'main' => true]);
        $pinturaCTC = Task::create(['name' => 'Pintura de CTC', 'main' => false]);
        $impermeabilizacionCTC = Task::create(['name' => 'Impermeabilizacion de CTC', 'main' => false]);
        $recibidoMateriales = Task::create(['name' => 'Se recibieron materiales', 'main' => false]);

        $pinturaCTC->validations()->attach([$si->id, $no->id]);
        $impermeabilizacionCTC->validations()->attach([$si->id, $no->id]);
        $recibidoMateriales->validations()->attach([$si->id, $no->id]);

        $mantenimientoGenerales->subtasks()->attach([$pinturaCTC->id, $impermeabilizacionCTC->id, $recibidoMateriales->id]);
    }
}
