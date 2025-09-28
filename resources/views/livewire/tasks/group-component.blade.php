<div class="min-h-screen dark:bg-slate-900 font-sans p-8 text-gray-900 dark:text-gray-100">
    <!-- Dashboard Header -->
    <div class="flex flex-col md:flex-row gap-2 items-center justify-between mb-8">
        <h1 class="text-3xl font-bold tracking-tight text-blue-800 dark:text-blue-400">Administrador de Grupos</h1>
        <div class="flex space-x-4">
            <!-- Button to create module -->
            @include('auth._auth')
        </div>
    </div>

    <!-- Main Container -->
    <div class="flex flex-col gap-8">
        <!-- modules and simple progress section -->
        <div class="md:col-span-2 space-y-8">
            <!-- module Progress Summary -->
            <div class="bg-white px-5 dark:bg-slate-800 rounded-xl shadow-lg py-6 flex flex-col items-center justify-center transition-all duration-300 transform hover:scale-[1.01]">
                <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Grupos</h2>
                @include('partials._validation')
                <div class="flex flex-col items-center justify-center text-center">
                    <form wire:submit.prevent="{{ $group_id ? 'update('.$group_id.')' : 'save()' }}" method="post">
                        <div class="md:grid md:grid-cols-3 flex flex-col gap-5 items-center justify-center w-full">
                            <label for="name">
                                Nombre:
                            </label>
                            <input type="text" name="name" wire:model="name" id="name" class="w-full md:w-auto rounded-lg px-3 py2 text-blue-950">
                            <div>
                                <label for="tasks" class="text-sm font-medium text-gray-700">Tareas</label>
                                <select id="tasks" wire:model="selectedTasks" multiple class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-blue-950">
                                    @foreach($tasks as $task)
                                        <option value="{{ $task->id }}">{{ $task->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="col-span-2 w-full text-white md:w-auto px-3 py-2 rounded-lg bg-amber-600 flex flex-row gap-2 items-center justify-center">@include('icons.save') Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- module List -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Listado de Grupos</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">Nombre</th>
                                <th scope="col" class="px-6 py-3">Tareas</th>
                                <th scope="col" class="px-6 py-3">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($groups as $group)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">{{ $group->name }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col md:flex-row gap-2">
                                            @foreach($group->tasks as $task)
                                                <p class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-lg">{{ $task->name }}</p>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 flex gap-2">
                                        <button class="gap-2 px-3 py-2 bg-yellow-400 text-gray-900 rounded-lg flex items-center justify-center" wire:click="edit({{ $group->id }}); document.getElementById('name').focus()">@include('icons.edit') Editar</button>
                                        <button class="gap-2 px-3 py-2 bg-red-500 text-white rounded-lg flex items-center justify-center" onclick="return confirm('¿Estás seguro de que quieres eliminar este elemento?') ? @this.call('destroy', {{ $group->id }}) : false;">@include('icons.delete') Eliminar</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-gray-500 dark:text-gray-400">No hay grupos para mostrar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
