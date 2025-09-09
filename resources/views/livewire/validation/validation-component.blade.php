<div class="min-h-screen dark:bg-slate-900 font-sans p-8 text-gray-900 dark:text-gray-100">
    <!-- Dashboard Header -->
    <div class="flex flex-col md:flex-row gap-2 items-center justify-between mb-8">
        <h1 class="text-3xl font-bold tracking-tight text-blue-800 dark:text-blue-400">Administrador de validaciones</h1>
        <div class="flex space-x-4">
            <!-- Button to create validation -->
            @if (Auth::user())
                <span class="p-4 text-blue-100 font-bold bg-amber-600 rounded-lg ">
                    {{ Auth::user()->name }} - {{ Auth::user()->location?Auth::user()->location->name : 'Sin ubicación asignada'}}
                </span>
            @endif
        </div>
    </div>

    <!-- Main Container -->
    <div class="flex flex-col gap-8">
        <!-- validations and simple progress section -->
        <div class="md:col-span-2 space-y-8">
            <!-- validation Progress Summary -->
            <div class="bg-white p-5 dark:bg-slate-800 rounded-xl shadow-lg py-6 flex flex-col items-center justify-center transition-all duration-300 transform hover:scale-[1.01]">
                <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Validaciones</h2>
                <div class="flex flex-col items-center justify-center text-center">
                    <form action="" method="post" x-data="{value:'', name:'', task:0}">
                        <div class="grid md:grid-cols-4 grid-cols-1 gap-5 items-center justify-center w-full">
                            <label for="name">
                                Nombre de la validación:
                            </label>
                            <input type="text" name="name" x-model="name" id="name" class="w-full md:w-auto rounded-lg px-3 py2 text-blue-950">
                            <label for="name">
                                Valor de la validación:
                            </label>
                            <input type="text" name="value" x-model="value" id="value" class="w-full md:w-auto rounded-lg px-3 py2 text-blue-950">
                            <label for="task">
                                ¿Actividad a la que pertenece?:
                            </label>
                            <select name="task" x-model="task" id="task"  class="w-full md:w-auto rounded-lg px-3 py2 text-blue-950">
                                @foreach ($tasks as $t)
                                    <option value="{{ $t->id }}"> {{$t->main ? '': $t->mainTasks()->first()->name.' ->'}} {{$t->name}} - {{$t->main ? 'Principal':'Subtarea'}}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="col-span-2 w-full text-white md:w-auto px-3 py-2 rounded-lg bg-amber-600 flex flex-row gap-2">@include('icons.save') Guardar</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- validation List -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Listado de validaciones</h2>
                <ul class="space-y-4">
                    @forelse ($validations as $validation)
                        <li class="flex flex-col gap-4 md:flex-row items-center justify-between p-4 bg-gray-50 dark:bg-slate-700 rounded-lg shadow-sm transition-transform transform hover:scale-[1.01] hover:shadow-md">
                            <span class="md:w-3/4 w-full">{{ $validation->name }} : {{$validation->value}}</span>
                            <button class="px-3 py-2 bg-yellow-400 text-gray-900 rounded-lg flex flex-row gap-2">@include('icons.edit') Editar</button>
                            <button class="px-3 py-2 bg-red-500 text-white rounded-lg flex flex-row gap-2">@include('icons.delete') Eliminar</button>
                        </li>
                        <p>Asignaciones para {{$validation->name}} : {{$validation->value}}</p>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-2 space-y-2">
                            @foreach ($validation->tasks as $task )
                            <span class="flex flex-col gap-4 md:flex-row items-center justify-between p-2 bg-gray-50 dark:bg-slate-700 rounded-lg shadow-sm transition-transform transform hover:scale-[1.01] hover:shadow-md">
                                <span class="text-amber-500 ml-2 md:w-3/4 w-full">{{ $task->name }}</span>
                                <button class="px-3 py-2 bg-red-500 text-white rounded-lg flex flex-row gap-2">@include('icons.delete') Eliminar</button>
                            </span>
                            @endforeach
                        </div>
                    @empty
                        <li class="text-center text-gray-500 dark:text-gray-400">No hay validaciones para mostrar.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
