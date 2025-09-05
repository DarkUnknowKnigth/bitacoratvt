<div class="min-h-screen bg-slate-100 dark:bg-slate-900 font-sans p-8 text-gray-900 dark:text-gray-100">
    <!-- Dashboard Header -->
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold tracking-tight text-blue-800 dark:text-blue-400">Dashboard de Tareas</h1>
        <div class="flex space-x-4">
            <!-- Button to create task -->
            <button wire:click="$toggle('showForm')"
                    class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-lg transition-transform transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-blue-500/50">
                Crear Tarea
            </button>
        </div>
    </div>

    <!-- Main Container -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Tasks and simple progress section -->
        <div class="md:col-span-2 space-y-8">
            <!-- Task Progress Summary -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6 flex flex-col items-center justify-center transition-all duration-300 transform hover:scale-[1.01]">
                <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Avance de Tareas</h2>
                <div class="flex flex-col items-center justify-center text-center">
                    <span class="text-3xl font-bold text-blue-700 dark:text-blue-400">{{ $completedTasksCount }}</span>
                    <span class="text-sm text-gray-500 dark:text-gray-400">de {{ count($tasks) }} completadas</span>
                </div>
            </div>

            <!-- Task List -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Listado de Tareas</h2>
                <ul class="space-y-4">
                    @forelse ($tasks as $task)
                        <li class="flex items-center justify-between p-4 bg-gray-50 dark:bg-slate-700 rounded-lg shadow-sm transition-transform transform hover:scale-[1.01] hover:shadow-md">
                            <span class="text-lg {{ $task['completed'] ? 'line-through text-gray-500' : '' }}">{{ $task['name'] }}</span>
                            <button wire:click="toggleTaskStatus({{ $task['id'] }})"
                                    class="p-2 rounded-full {{ $task['completed'] ? 'bg-amber-500 hover:bg-amber-600' : 'bg-blue-600 hover:bg-blue-700' }} text-white transition-colors duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $task['completed'] ? 'M5 13l4 4L19 7' : 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' }}" />
                                </svg>
                            </button>
                        </li>
                    @empty
                        <li class="text-center text-gray-500 dark:text-gray-400">No hay tareas para mostrar.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <!-- Control Column (Form) -->
        @if ($showForm)
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center p-4 z-50 transition-all duration-300">
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl p-8 w-full max-w-lg">
                    <h2 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-200">Crear Nueva Tarea</h2>
                    <form wire:submit.prevent="createTask" class="space-y-4">
                        <div class="flex flex-col">
                            <label for="taskName" class="text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Nombre de la Tarea</label>
                            <input type="text" id="taskName" wire:model="newTaskName" class="p-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('newTaskName') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex justify-end space-x-4">
                            <button type="button" wire:click="$toggle('showForm')" class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition-transform transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-gray-300/50">Cancelar</button>
                            <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-lg transition-transform transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-blue-500/50">Guardar Tarea</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
