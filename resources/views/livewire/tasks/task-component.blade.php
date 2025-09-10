<div class="min-h-screen dark:bg-slate-900 font-sans p-8 text-gray-900 dark:text-gray-100">
    <!-- Dashboard Header -->
    <div class="flex flex-col md:flex-row gap-2 items-center justify-between mb-8">
        <h1 class="text-3xl font-bold tracking-tight text-blue-800 dark:text-blue-400">Administrador de actividades</h1>
        <div class="flex space-x-4">
            <!-- Button to create task -->
            @if (Auth::user())
                <span class="p-4 text-blue-100 font-bold bg-amber-600 rounded-lg ">
                    {{ Auth::user()->name }} - {{ Auth::user()->location?Auth::user()->location->name : 'Sin ubicación asignada'}}
                </span>
            @endif
        </div>
    </div>

    <!-- Main Container -->
    <div class="flex flex-col gap-8">
        <!-- Tasks and simple progress section -->
        <div class="md:col-span-2 space-y-8">
            <!-- Task Progress Summary -->
            <div class="bg-white px-5 dark:bg-slate-800 rounded-xl shadow-lg py-6 flex flex-col items-center justify-center transition-all duration-300 transform hover:scale-[1.01]">
                <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Actividades</h2>
                <div class="flex flex-colitems-center justify-center text-center">
                    <form wire:submit="{{$task_id?'update('.$task_id.')':'save()'}}" method="post" x-data="{main:1}">
                        <div class="flex flex-col md:grid md:grid-cols-4 gap-5 items-center justify-center w-full">
                            <label for="name">
                                Nombre de la actividad:
                            </label>
                            <input type="text" name="name"wire:model="name" id="name" class="w-full md:w-auto rounded-lg px-3 py2 text-blue-950">
                            <label for="main">
                                ¿Es una actividad principal?:
                            </label>
                            <select name="main"wire:model="main" x-model="main" id="main"  class="w-full md:w-auto rounded-lg px-3 py2 text-blue-950">
                                <option value="1">Si</option>
                                <option value="0">No</option>
                            </select>

                            <label x-show="main==0" for="parent">
                                ¿Actividad principal a la que pertenece?:
                            </label>
                            <select x-show="main==0" name="parent"wire:model="parent" id="parent"  class="w-full md:w-auto rounded-lg px-3 py2 text-blue-950">
                                @foreach ($mainTasks as $t)
                                    <option value="{{ $t->id }}">{{$t->name}}</option>
                                @endforeach
                            </select>
                            <span col="col-span-2"></span>
                            <button type="submit" class="col-span-2 w-full text-white md:w-auto px-3 py-2 rounded-lg bg-amber-600 flex flex-row gap-2">@include('icons.save') Guardar</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Task List -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Listado de Tareas</h2>
                <ul class="space-y-4">
                    @forelse ($tasks as $task)
                        <li class="flex flex-col gap-4 md:flex-row items-center justify-between p-4 bg-gray-50 dark:bg-slate-700 rounded-lg shadow-sm transition-transform transform hover:scale-[1.01] hover:shadow-md">
                            <span class="md:w-3/4 w-full">{{ $task->name }}</span>
                            <button class="px-3 py-2 bg-yellow-400 text-gray-900 rounded-lg flex flex-row gap-2">@include('icons.edit') Editar</button>
                            <button class="px-3 py-2 bg-red-500 text-white rounded-lg flex flex-row gap-2">@include('icons.delete') Eliminar</button>
                        </li>
                        @if ($task->validations->count() > 0)
                            <li class="gap-4 md:grid md:grid-cols-4 w-full flex flex-col items-center justify-between p-4 bg-gray-50 dark:bg-slate-700 rounded-lg shadow-sm transition-transform transform hover:scale-[1.01] hover:shadow-md">
                                <span class="px-3 py-2 rounded-lg md:col-span-4 col-span-2">Validaciones de {{ $task->name }}:</span>
                                @foreach ($task->validations as $v)
                                    <span class="px-3 py-2 rounded-lg bg-amber-500 text-gray-900 flex md:flex-row flex-col justify-between gap-2">
                                        Texto: {{$v->name}} <br> Valor: {{$v->value}}
                                        <button class="px-3 py-2 bg-red-500 text-white rounded-lg flex flex-row gap-2">@include('icons.delete') Eliminar</button>
                                    </span>
                                @endforeach
                            </li>
                        @endif
                        <ul class="space-y-2">
                            @foreach ($task->subtasks as $st )
                                <li class="flex flex-col gap-4 w-full md:flex-row items-center justify-between p-4 bg-gray-50 dark:bg-slate-700 rounded-lg shadow-sm transition-transform transform hover:scale-[1.01] hover:shadow-md">
                                    <span class="text-amber-500 ml-2 md:w-3/4 w-full">{{ $st->name }}</span>
                                    <button class="px-3 py-2 bg-yellow-400 text-gray-900 rounded-lg flex flex-row gap-2">@include('icons.edit') Editar</button>
                                    <button class="px-3 py-2 bg-red-500 text-white rounded-lg flex flex-row gap-2">@include('icons.delete') Eliminar</button>
                                </li>
                                @if ($st->validations->count() > 0)
                                    <li class="gap-4 md:grid md:grid-cols-4 flex flex-col items-center justify-between p-4 bg-gray-50 dark:bg-slate-700 rounded-lg shadow-sm transition-transform transform hover:scale-[1.01] hover:shadow-md">
                                        <span class="px-3 py-2 rounded-lg md:col-span-4 col-span-2">Validaciones de {{ $st->name }}:</span>
                                        @foreach ($st->validations as $v)
                                            <span class="px-3 py-2 rounded-lg bg-amber-500 text-gray-900 flex md:flex-row flex-col justify-between gap-2">
                                                Texto: {{$v->name}} <br> Valor: {{$v->value}}
                                                <button class="px-3 py-2 bg-red-500 text-white rounded-lg flex flex-row gap-2">@include('icons.delete') Eliminar</button>
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
