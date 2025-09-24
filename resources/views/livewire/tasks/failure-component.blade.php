<div class="min-h-screen dark:bg-slate-900 font-sans p-8 text-gray-900 dark:text-gray-100">
    <!-- Dashboard Header -->
    <div class="flex flex-col md:flex-row gap-2 items-center justify-between mb-8">
        <h1 class="text-3xl font-bold tracking-tight text-blue-800 dark:text-blue-400">Administrador de Fallas</h1>
        <div class="flex space-x-4">
            @include('auth._auth')
        </div>
    </div>

    <!-- Main Container -->
    <div class="flex flex-col gap-8">
        <div class="md:col-span-2 space-y-8">
            <!-- Formulario de Fallas -->
            <div class="bg-white p-5 dark:bg-slate-800 rounded-xl shadow-lg py-6 flex flex-col items-center justify-center transition-all duration-300 transform hover:scale-[1.01]">
                <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">
                    {{ $failure_id ? 'Editar Falla' : 'Registrar Nueva Falla' }}
                </h2>
                @include('partials._validation')
                <div class="flex flex-col items-center justify-center text-center w-full">
                    <form wire:submit="{{ $failure_id ? 'update' : 'save' }}" method="post" class="w-full">
                        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 items-start">
                            <!-- Tarea Principal -->
                            <div>
                                <label for="task_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tarea Principal</label>
                                <select id="task_id" wire:model.live="task_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="">Selecciona una tarea</option>
                                    @foreach ($tasks as $task)
                                        <option value="{{ $task->id }}">{{ $task->name }}</option>
                                    @endforeach
                                </select>
                                @error('task_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Subtarea -->
                            <div>
                                <label for="subtask_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Subtarea (Opcional)</label>
                                <select id="subtask_id" wire:model="subtask_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="">Selecciona una subtarea</option>
                                    @foreach ($subtasks as $subtask)
                                        <option value="{{ $subtask->id }}">{{ $subtask->name }}</option>
                                    @endforeach
                                </select>
                                @error('subtask_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Sucursal -->
                            <div>
                                <label for="location_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Sucursal</label>
                                <select id="location_id" wire:model="location_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="">Selecciona una sucursal</option>
                                    @foreach ($locations as $location)
                                        <option value="{{ $location->id }}">{{ $location->name }}</option>
                                    @endforeach
                                </select>
                                @error('location_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Fecha -->
                            <div>
                                <label for="date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Fecha</label>
                                <input type="date" id="date" wire:model="date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                @error('date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Descripción -->
                            <div class="md:col-span-2">
                                <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Descripción de la Falla</label>
                                <textarea id="description" wire:model="description" rows="1" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
                                @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Resuelto -->
                            <div class="flex items-center mt-6">
                                <input id="solved" type="checkbox" wire:model="solved" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="solved" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">¿Marcar como resuelta?</label>
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="mt-6 flex flex-col md:flex-row gap-4 justify-center">
                            <button type="submit" class="w-full md:w-auto text-white px-5 py-2.5 rounded-lg bg-amber-600 hover:bg-amber-700 flex flex-row gap-2 items-center justify-center">
                                @include('icons.save') Guardar Falla
                            </button>
                            @if ($failure_id)
                                <button type="button" wire:click="resetForm" class="w-full md:w-auto text-white px-5 py-2.5 rounded-lg bg-gray-500 hover:bg-gray-600 flex flex-row gap-2 items-center justify-center">
                                    @include('icons.cancel') Cancelar Edición
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Listado de Fallas -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6">
                <div class="flex flex-col md:flex-row items-center justify-between mb-4 gap-4">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Listado de Fallas</h2>
                    <div class="flex-grow md:max-w-md">
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar por descripción, tarea..." class="w-full rounded-lg px-3 py-2 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <select wire:model.live="statusFilter" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full md:w-auto p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="">Todos los estados</option>
                        <option value="0">Pendientes</option>
                        <option value="1">Resueltas</option>
                    </select>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">Fecha</th>
                                <th scope="col" class="px-6 py-3">Tarea / Subtarea</th>
                                <th scope="col" class="px-6 py-3">Sucursal</th>
                                <th scope="col" class="px-6 py-3">Descripción</th>
                                <th scope="col" class="px-6 py-3">Reportó</th>
                                <th scope="col" class="px-6 py-3">Estado</th>
                                <th scope="col" class="px-6 py-3">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($failures as $failure)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($failure->date)->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4">{{ $failure->task->name }} {{ $failure->subtask ? '-> '.$failure->subtask->name : '' }}</td>
                                    <td class="px-6 py-4">{{ $failure->location->name }}</td>
                                    <td class="px-6 py-4 max-w-xs truncate">{{ $failure->description }}</td>
                                    <td class="px-6 py-4">{{ $failure->user->name }}</td>
                                    <td class="px-6 py-4">
                                        <button wire:click="toggleSolved({{ $failure->id }})" class="px-3 py-1 rounded-full text-xs font-semibold {{ $failure->solved ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                                            {{ $failure->solved ? 'Resuelta' : 'Pendiente' }}
                                        </button>
                                    </td>
                                    <td class="px-6 py-4 flex gap-2">
                                        <button class="px-3 py-2 bg-yellow-400 text-gray-900 rounded-lg flex items-center justify-center" wire:click="edit({{ $failure->id }}); document.getElementById('task_id').focus()">@include('icons.edit')</button>
                                        <button class="px-3 py-2 bg-red-500 text-white rounded-lg flex items-center justify-center" onclick="return confirm('¿Estás seguro de que quieres eliminar esta falla?') ? @this.call('destroy', {{ $failure->id }}) : false;">@include('icons.delete')</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-gray-500 dark:text-gray-400">No hay fallas para mostrar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
