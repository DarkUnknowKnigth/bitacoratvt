
<div class="min-h-screen dark:bg-slate-900 font-sans p-8 text-gray-900 dark:text-gray-100">
    <!-- Dashboard Header -->
    <div class="flex flex-col md:flex-row gap-2 items-center justify-between mb-8">
        <h1 class="text-3xl font-bold tracking-tight text-blue-800 dark:text-blue-400">Actividades</h1>
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
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6 flex flex-col items-center justify-center transition-all duration-300 transform hover:scale-[1.01]">
                <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Avance de Tareas</h2>
                <div class="flex flex-col items-center justify-center text-center">
                    <span class="text-3xl font-bold text-blue-700 dark:text-blue-400">{{ $completedTasksCount }}</span>
                    <span class="text-sm text-gray-500 dark:text-gray-400">de {{ $allTasks }} completadas</span>
                </div>
            </div>

            <!-- Task List -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Listado de Tareas</h2>
                <ul class="space-y-4">
                    @forelse ($tasks as $task)
                        <li class="flex items-center justify-between p-4 bg-gray-50 dark:bg-slate-700 rounded-lg shadow-sm transition-transform transform hover:scale-[1.01] hover:shadow-md">
                            <span>{{ $task->name }}</span>
                            @if ($task->validations->count() > 1)
                                <select class="text-amber-700 rounded-lg px-3 py-2 w-full md:w-1/4" name="subtask-{{ $task->id }}" id="{{ $task->id.'.'.$task->id }}">
                                    <option value="">Selecciona</option>
                                    @foreach ($task->validations as $v)
                                        <option value="{{ $v->id }}">{{ $v->name }}</option>
                                    @endforeach
                                </select>
                            @else
                                @if ($task->validations->count() > 0)
                                    <input type="{{ $task->validations->first()->value }}" class="text-amber-700 rounded-lg px-3 py-2 w-full md:w-1/4" name="subtask-{{ $task->id }}" id="{{ $task->id.'.'.$task->id }}" placeholder="{{ $task->validations->first()->name }}">
                                @endif
                            @endif
                            <input type="text" class="text-amber-700 rounded-lg px-3 py-2 w-full md:w-1/4 mt-2 md:mt-0" name="comment-{{ $task->id }}" id="comment-{{ $task->id.'.'.$task->id }}" placeholder="Comentario (opcional)">
                            @if ($task->reviews->where([['date',$nowFormated],['task_id', $task->id]])->count() > 0)
                                @php
                                    $rw = $task->reviews->where([['date',$nowFormated],['task_id', $task->id]])->first();
                                @endphp
                                <span class="text-amber-600 font-semibold">{{$rw->user->name}} {{$rw->user->date}}: {{ $rw->validation->name }} - {{ $rw->validation->comment }}</span>
                            @else
                                <button class=" bg-amber-500 text-black rounded-lg px-3 py-2 flex flex-row gap-2">
                                    @include('icons.validate')
                                    Validar
                                </button>
                            @endif
                        </li>
                        <ul class="space-y-2">
                            @foreach ($task->subtasks as $st )
                            <li class="flex flex-col gap-4 md:flex-row items-center justify-between p-4 bg-gray-50 dark:bg-slate-700 rounded-lg shadow-sm transition-transform transform hover:scale-[1.01] hover:shadow-md">
                                <span class="text-amber-500 ml-2 md:w-1/4 w-full">{{ $st->name }}</span>
                                @if ($st->validations->count() > 1)
                                    <select class="text-amber-700 rounded-lg px-3 py-2 w-full md:w-1/4" name="subtask-{{ $st->id }}" id="{{ $task->id.'.'.$st->id }}">
                                        <option value="">Selecciona</option>
                                        @foreach ($st->validations as $v)
                                            <option value="{{ $v->id }}">{{ $v->name }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <input type="{{ $st->validations->first()->value }}" class="text-amber-700 rounded-lg px-3 py-2 w-full md:w-1/4" name="subtask-{{ $st->id }}" id="{{ $task->id.'.'.$st->id }}" placeholder="{{ $st->validations->first()->name }}">
                                @endif
                                <input type="text" class="text-amber-700 rounded-lg px-3 py-2 w-full md:w-1/4 mt-2 md:mt-0" name="comment-{{ $st->id }}" id="comment-{{ $task->id.'.'.$st->id }}" placeholder="Comentario (opcional)">
                                @if ($st->reviews->where([['date',$nowFormated],['task_id', $st->id]])->count() > 0)
                                    @php
                                        $rw = $st->reviews->where([['date',$nowFormated],['task_id', $st->id]])->first();
                                    @endphp
                                    <span class="text-green-600 font-semibold">{{$rw->user->name}} {{$rw->user->date}}: {{ $rw->validation->name }} - {{ $rw->validation->comment }}</span>
                                @else
                                    <button class=" bg-amber-500 text-black rounded-lg px-3 py-2 flex flex-row gap-2">
                                        @include('icons.validate')
                                        Validar
                                    </button>
                                @endif
                            </li>
                            @endforeach
                        </ul>
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
