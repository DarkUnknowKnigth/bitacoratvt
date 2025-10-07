<div class="min-h-screen dark:bg-slate-900 font-sans p-8 text-gray-900 dark:text-gray-100">
    @php
        $taskColors = [
            'bg-blue-50 dark:bg-blue-900',
            'bg-amber-50 dark:bg-amber-900'
        ];
        $subtaskColors = ['bg-blue-200 dark:bg-blue-700', 'bg-amber-200 dark:bg-amber-700']
    @endphp
    <!-- Dashboard Header -->
    <div class="flex flex-col md:flex-row gap-2 items-center justify-between mb-8">
        <h1 class="text-3xl font-bold tracking-tight text-blue-800 dark:text-blue-400">Administrador de actividades</h1>
        <div class="flex space-x-4">
            <!-- Button to create task -->
            @include('auth._auth')
        </div>
    </div>

    <!-- Main Container -->
    <div class="flex flex-col gap-8">
        <!-- Tasks and simple progress section -->
        <div class="md:col-span-2 space-y-8">
            <!-- Task Progress Summary -->
            <div class="bg-white px-5 dark:bg-slate-800 rounded-xl shadow-lg py-6 flex flex-col items-center justify-center transition-all duration-300 transform hover:scale-[1.01]">
                <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Actividades</h2>
                @include('partials._validation')
                <div class="flex flex-col items-center justify-center text-center">
                    <form wire:submit="{{$task_id?'update('.$task_id.')':'save()'}}" method="post">
                        <div class="flex flex-col md:grid md:grid-cols-4 gap-5 items-start justify-center w-full">
                            <label for="name">
                                Nombre de la actividad:
                            </label>
                            <input type="text" name="name" wire:model="name" id="name" class="w-full md:w-auto rounded-lg px-3 py-2 text-blue-950">
                            <label for="main">
                                ¿Es una actividad principal?:
                            </label>
                            <select name="main" wire:model="main" x-model="main" id="main"  class="w-full md:w-auto rounded-lg px-3 py-2 text-blue-950">
                                <option value="1">Si</option>
                                <option value="0">No</option>
                            </select>
                            <label wire:show="main==0" for="parent">
                                ¿Actividad principal a la que pertenece?:
                            </label>
                            <select wire:show="main==0" name="parent" wire:model="parent" id="parent"  class="w-full md:w-auto rounded-lg px-3 py-2 text-blue-950">
                                <option value="">Selecciona una tarea</option>
                                @foreach ($mainTasks as $t)
                                    <option value="{{ $t->id }}">{{$t->name}}</option>
                                @endforeach
                            </select>
                            <label for="location_ids">
                                ¿A que sucursal(es) pertenece?:
                            </label>
                            <select name="location_ids" wire:model="location_ids" id="location_ids" multiple class="w-full md:w-auto rounded-lg px-3 py-2 text-blue-950 h-32">
                                <option disabled value="">Global (todas las sucursales)</option>
                                @foreach ($locations as $location)
                                    <option value="{{ $location->id }}">{{$location->name}}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="col-span-2 w-full text-white md:w-auto px-3 py-2 rounded-lg bg-amber-600 flex flex-row gap-2">@include('icons.save') Guardar</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Task List -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6">
                <div class="flex md:flex-row flex-col items-center justify-between text-center mb-4 gap-4">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Listado de Tareas</h2>
                    <div class="flex-grow">
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar tarea por nombre..." class="w-full rounded-lg px-3 py-2 text-blue-950 border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <button type="button" wire:click="deleteErrors()" class="col-span-2 w-full text-white md:w-auto px-3 py-2 rounded-lg bg-purple-600 flex flex-row gap-2">@include('icons.tool') Borrar errores</button>
                </div>

                <ul class="space-y-4">
                    @forelse($tasks as $i => $task)
                        @php
                            $custom_color =  ($i+1)%2 == 0 ? $taskColors[1] : $taskColors[0];
                        @endphp
                        <li class="flex flex-col gap-4 md:flex-row items-center justify-between p-4 {{ $custom_color }} rounded-lg shadow-sm transition-transform transform hover:scale-[1.01] hover:shadow-md">
                            <div class="md:w-1/2 w-full">
                                <p class="font-bold">{{ $task->name }}</p>
                                <div class="flex flex-wrap gap-1 mt-2">
                                    @forelse ($task->locations as $location)
                                        <span class="text-xs bg-blue-200 text-blue-800 dark:bg-blue-700 dark:text-blue-200 px-2 py-1 rounded-full">{{ $location->name }}</span>
                                    @empty
                                        <span class="text-xs bg-gray-200 text-gray-800 dark:bg-gray-600 dark:text-gray-200 px-2 py-1 rounded-full">Global</span>
                                    @endforelse
                                </div>
                            </div>
                            <button class="px-3 py-2 bg-yellow-400 text-gray-900 rounded-lg flex flex-row gap-2 md:w-auto w-full items-center justify-center"
                                wire:click="edit({{ $task->id }}); document.getElementById('name').focus()"
                            >@include('icons.edit') Editar</button>
                            <button class="px-3 py-2 bg-red-500 text-white rounded-lg flex flex-row gap-2 md:w-auto w-full items-center justify-center"
                                onclick="return confirm('¿Estás seguro de que quieres eliminar este elemento, se eliminar las bitácoras de este elemento y las subtereas y sus respectivas bitácoras?') ? @this.call('destroy', {{ $task->id }}) : false;"
                            >@include('icons.delete') Eliminar</button>
                            <select name="validation" wire:model="validation" id="validation"  class="w-full md:w-auto rounded-lg px-3 py-2 text-blue-950">
                                <option value="">Validaciones</option>
                                @foreach ($validations as $v)
                                    <option value="{{ $v->id }}">{{$v->name}}:({{$v->value}})</option>
                                @endforeach
                            </select>
                            <button class="px-3 py-2 bg-blue-500 text-white rounded-lg flex flex-row gap-2 md:w-auto w-full items-center justify-center"
                                wire:click="addValidation({{ $task->id }});"
                            >@include('icons.add') Validación</button>
                        </li>
                        @if ($task->validations->count() > 0)
                            <li class="gap-4 md:grid md:grid-cols-4 w-full flex flex-col items-center justify-between p-4 bg-gray-50 dark:bg-slate-700 rounded-lg shadow-sm transition-transform transform hover:scale-[1.01] hover:shadow-md">
                                <span class="px-3 py-2 rounded-lg md:col-span-4 col-span-2">Validaciones de {{ $task->name }}:</span>
                                @foreach ($task->validations as $v)
                                    <span class="px-3 py-2 rounded-lg bg-amber-500 text-gray-900 flex md:flex-row flex-col justify-between gap-2">
                                        Texto: {{$v->name}} <br> Valor: {{$v->value}}
                                        <button class="px-3 py-2 bg-red-500 text-white rounded-lg flex flex-row gap-2 md:w-auto w-full items-center justify-center"
                                            onclick="return confirm('¿Estás seguro de que quieres eliminar este elemento?') ? @this.call('unvalidate',{{ $task->id }},{{ $v->id }}) : false;"
                                        >@include('icons.delete') Eliminar</button>
                                    </span>
                                @endforeach
                            </li>
                        @endif
                        <ul class="space-y-2">
                            @foreach ($task->subtasks->sortBy('name') as $index => $st )
                                <li class="ml-4 flex flex-col gap-4 w-full md:flex-row items-center justify-between {{ ($index+1)%2 == 0 ? $subtaskColors[0] : $subtaskColors[1] }} p-4 rounded-lg shadow-sm transition-transform transform hover:scale-[1.01] hover:shadow-md">
                                    <span class="dark:text-amber-500 text-amber-800 ml-2 md:w-3/4 w-full">
                                        <span>
                                            {{ $st->name }}
                                        </span>
                                        <div class="flex flex-wrap gap-1 mt-2">
                                        @forelse ($st->locations as $slocation)
                                            <span class="text-xs bg-blue-200 text-blue-800 dark:bg-blue-700 dark:text-blue-200 px-2 py-1 rounded-full">{{ $slocation->name }}</span>
                                        @empty
                                            <span class="text-xs bg-gray-200 text-gray-800 dark:bg-gray-600 dark:text-gray-200 px-2 py-1 rounded-full">Global</span>
                                        @endforelse
                                </div>
                                    </span>
                                    <button class="px-3 py-2 bg-yellow-400 text-gray-900 rounded-lg flex flex-row gap-2 md:w-auto w-full items-center justify-center"
                                        wire:click="edit({{ $st->id }}); document.getElementById('name').focus()"
                                    >@include('icons.edit') Editar</button>
                                    <button class="px-3 py-2 bg-red-500 text-white rounded-lg flex flex-row gap-2 md:w-auto w-full items-center justify-center"
                                        onclick="return confirm('¿Estás seguro de que quieres eliminar este elemento, se eliminar las bitacoras de este elemento?') ? @this.call('destroy', {{ $st->id }}) : false;"
                                    >@include('icons.delete') Eliminar</button>
                                    <select name="validation" wire:model="validation" id="validation"  class="w-full md:w-auto rounded-lg px-3 py-2 text-blue-950">
                                        <option value="">Validaciones</option>
                                        @foreach ($validations as $v)
                                            <option value="{{ $v->id }}">{{$v->name}}:({{$v->value}})</option>
                                        @endforeach
                                    </select>
                                    <button class="px-3 py-2 bg-blue-500 text-white rounded-lg flex flex-row gap-2 md:w-auto w-full items-center justify-center"
                                        wire:click="addValidation({{ $st->id }});"
                                    >@include('icons.add') Validación</button>
                                </li>
                                @if ($st->validations->count() > 0)
                                    <li class="ml-8 gap-4 md:grid md:grid-cols-4 flex flex-col items-center justify-between p-4 {{ ($index+1)%2 == 0 ? $subtaskColors[0] : $subtaskColors[1] }} rounded-lg shadow-sm transition-transform transform hover:scale-[1.01] hover:shadow-md">
                                        <span class="px-3 py-2 rounded-lg md:col-span-4 col-span-2">Validaciones de {{ $st->name }}:</span>
                                        @foreach ($st->validations as $v)
                                            <span class="px-3 py-2 rounded-lg bg-amber-500 text-gray-900 flex md:flex-row flex-col justify-between gap-2 md:w-auto w-full items-center">
                                                Texto: {{$v->name}} <br> Valor: {{$v->value}}
                                                <button class="px-3 py-2 bg-red-500 text-white rounded-lg flex flex-row gap-2 md:w-auto w-full items-center justify-center"
                                                    onclick="return confirm('¿Estás seguro de que quieres eliminar este elemento?') ? @this.call('unvalidate',{{ $st->id }},{{ $v->id }}) : false;"
                                                >@include('icons.delete') Eliminar</button>
                                            </span>
                                        @endforeach
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    @empty
                        <li class="text-center text-gray-500 dark:text-gray-400">No hay tareas para mostrar.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
