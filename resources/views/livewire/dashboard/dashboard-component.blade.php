<div class="min-h-screen dark:bg-slate-900 font-sans p-8 text-gray-900 dark:text-gray-100">
    <!-- Dashboard Header -->
    <div class="flex flex-col md:flex-row gap-2 items-center justify-between mb-8">
        <h1 class="text-3xl font-bold tracking-tight text-blue-800 dark:text-blue-400">Actividades para sucursal
            {{auth()->user()->location->name}}</h1>
        <div class="flex space-x-4">
            <!-- Button to create task -->
            @include('auth._auth')
        </div>
    </div>

    <!-- Main Container -->
    <div class="flex flex-col gap-8"
        x-data="{ showingTask: @entangle('showingTask'), selectedBinnalce: @entangle('selectedBinnalce') }">
        <!-- Tasks and simple progress section -->
        <div class="md:col-span-2 space-y-8">
            <!-- Task Progress Summary -->
            <div>
                <h1>Posees múltiples bitácoras, porfavor selecciona una bitácora</h1>
                <select x-model="selectedBinnalce" name="binnacle_selector" id="binnacle_selector" wire:change="updateTasks()"
                    class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">Seleccionar</option>
                    @foreach ($binnacles as $binnacle)
                    <option value="{{ $binnacle->id }}">{{ $binnacle->name }}</option>
                    @endforeach
                </select>
            </div>
            <div
                class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6 flex flex-col items-center justify-center transition-all duration-300 transform hover:scale-[1.01]">
                <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Avance de Tareas</h2>
                <div class="flex flex-col items-center justify-center text-center">
                    <span class="text-3xl font-bold text-blue-700 dark:text-blue-400">{{ $completedTasksCount }} /
                        {{$allTasks}}</span>
                </div>
            </div>

            <!-- Task List -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6" x-show="selectedBinnalce">
                <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Listado de Tareas</h2>
                @include('partials._validation')
                <ul class="space-y-4">
                    {{-- Iterar sobre los grupos --}}
                    @foreach ($groups as $group)
                    @if($group->tasks->count() > 0)
                    <div class="bg-blue-100 dark:bg-blue-900 rounded-lg p-2 my-4">
                        <h3 class="px-4 py-2 text-lg font-bold text-blue-800 dark:text-blue-300">
                            {{ $group->name }}
                        </h3>
                    </div>
                    @endif

                    @foreach ($group->tasks as $task)
                    {{-- Lógica de la Tarea --}}
                    <li wire:click="toggleTask('{{ $task->id }}')"
                        class="flex md:flex-row flex-col gap-2 items-center justify-between p-4 bg-orange-50 dark:bg-blue-950 rounded-lg shadow-sm transition-transform transform hover:scale-[1.01] hover:shadow-md">
                        <span class="md:w-3/4 font-bold text-lg">
                            {{ $task->name }}
                        </span>
                        @if ($task->completedReview($nowFormated, auth()->user()->id)->count() > 0)
                        @php
                        $rw = $task->completedReview($nowFormated, auth()->user()->id)->first();
                        @endphp
                        <span class="text-amber-600 w-full flex gap-5 font-semibold">
                            <span>
                                Validado por <b>{{$rw->user->name}}</b>

                                a las {{$rw->date}} {{ $rw->time }}

                                @if (isset($rw->validation->id))

                                indico el valor de <b>{{ $rw->validation->value ?? '---'}}</b>
                                @else
                                indico el valor de <b>{{ $rw->value ?? '---' }}</b>

                                @endif
                                y dijo {{ $rw->comments ?? '---' }}
                            </span>
                        </span>
                        @else
                        {{-- <input type="text" class="text-amber-700 rounded-lg px-3 py-2 w-full md:w-1/8 mt-2 md:mt-0"
                            name="comment-{{ $task->id }}" id="comment-{{ $task->id.'.'.$task->id }}"
                            placeholder="Comentario (opcional)" wire:model="comments.t-{{$task->id}}">
                        <div x-data="{ loading: false }" class="md:w-1/8 w-full">
                            <button
                                class="bg-amber-500 text-black rounded-lg px-3 py-2 flex flex-row gap-2 text-center items-center justify-center w-full"
                                x-on:click="loading = true; getLocationAndReview({{ $task->id }}, null)"
                                x-bind:disabled="loading" x-bind:class="{ 'opacity-50 cursor-not-allowed': loading }">
                                <span x-show="!loading">@include('icons.save') Comentario general</span>
                                <span x-show="loading">Obteniendo ubicación...</span>
                            </button>
                        </div> --}}
                        @endif
                        <span x-show="showingTask != '{{ $task->id }}'">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                            </svg>
                        </span>
                        <span x-show="showingTask == '{{ $task->id }}'">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                            </svg>
                        </span>
                    </li>
                    <ul class="space-y-2" x-show="showingTask == '{{ $task->id }}'">
                        @foreach ($task->subtasks as $st )
                        <li
                            class="flex flex-col gap-4 md:flex-row items-center justify-between p-4 bg-gray-50 dark:bg-slate-700 rounded-lg shadow-sm transition-transform transform hover:scale-[1.01] hover:shadow-md">
                            <span class="text-amber-500 ml-2 md:w-5/8 w-full">
                                <span class="font-bold flex flex-row justify-between">
                                    {{ $st->name }}
                                    <br>
                                </span>
                                <br>
                                <small>
                                    {{ $task->name }}
                                </small>
                            </span>
                            @if ($st->completedReview($nowFormated, auth()->user()->id, $task->id)->count() >
                            0)
                            @php
                            $rw = $st->completedReview($nowFormated, auth()->user()->id, $task->id)->first();
                            @endphp
                            <span class="text-green-600 w-full font-semibold">
                                <span>
                                    Validado por <b>{{$rw->user->name}}</b>

                                    a las {{$rw->date}} {{ $rw->time }}

                                    @if (isset($rw->validation->id))

                                    indico el valor de <b>{{ $rw->validation->value ?? '---'}}</b>
                                    @else
                                    indico el valor de <b>{{ $rw->value ?? '---' }}</b>

                                    @endif
                                    y dijo {{ $rw->comments ?? '---' }}
                                </span>
                            </span>
                            @else
                            @if ($st->validations->count() > 1)
                            <select class="text-amber-700 rounded-lg px-3 py-2 w-full md:w-1/8"
                                name="subtask-{{ $st->id }}" id="{{ $task->id.'.'.$st->id }}"
                                wire:model="validation_ids.st-{{$st->id}}">
                                <option value="">Seleciona</option>
                                @foreach ($st->validations as $v)
                                <option value="{{ $v->id }}">{{ $v->name }}</option>
                                @endforeach
                            </select>
                            @else
                            @if ($st->validations->count() > 0)
                            <input type="{{ $st->validations->first()->value }}"
                                class="text-amber-700 rounded-lg px-3 py-2 w-full md:w-1/8" name="subtask-{{ $st->id }}"
                                id="{{ $st->id.'.'.$st->id }}" wire:model="validationValues.st-{{$st->id}}"
                                placeholder="{{ $st->validations->first()->name }}">
                            @endif
                            @endif
                            <input type="text" class="text-amber-700 rounded-lg px-3 py-2 w-full md:w-1/8 mt-2 md:mt-0"
                                name="comment-{{ $st->id }}" id="comment-{{ $st->id.'.'.$st->id }}"
                                placeholder="Comentario (opcional)" wire:model="comments.st-{{$st->id}}">
                            <div class="flex flex-row gap-2 w-1/2 md:max-w-2/8">
                                <div x-data="{ loading: false }" class="w-1/2">
                                    <button class="bg-green-500 text-black rounded-lg text-xs px-3 py-2 w-full"
                                        x-on:click="loading = true; getLocationAndReview({{ $task->id }}, {{ $st->id }})"
                                        x-bind:disabled="loading"
                                        x-bind:class="{ 'opacity-50 cursor-not-allowed': loading }">
                                        <span x-show="!loading"
                                            class="flex flex-row gap-2 items-center justify-center">@include('icons.validate')
                                            Validar Tarea</span>
                                        <span x-show="loading">Obteniendo ubicación...</span>
                                    </button>
                                </div>
                                <a class=" bg-red-500 text-white rounded-lg px-3 text-xs py-2 flex flex-row gap-2 items-center justify-center w-1/2"
                                    href="{{route('failures', ['task_id' => $task->id, 'subtask_id' => $st->id])}}">
                                    @include('icons.cancel')
                                    Registrar Falla
                                </a>
                            </div>
                            @endif
                        </li>
                        @endforeach
                    </ul>
                    @endforeach
                    @endforeach

                    {{-- Iterar sobre las tareas sin grupo --}}
                    @if($tasksWithoutGroup->count() > 0)
                    <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-2 my-4">
                        {{-- <h3 class="px-4 py-2 text-lg font-bold text-gray-800 dark:text-gray-300">
                            Otras Tareas (Sin Grupo)
                        </h3> --}}
                    </div>
                    @endif

                    @foreach ($tasksWithoutGroup as $task)
                    {{-- Lógica de la Tarea --}}
                    <li wire:click="toggleTask('{{ $task->id }}')"
                        class="flex md:flex-row flex-col gap-2 items-center justify-between p-4 bg-orange-50 dark:bg-blue-950 rounded-lg shadow-sm transition-transform transform hover:scale-[1.01] hover:shadow-md">
                        <span class="md:w-3/4 font-bold text-lg">{{ $task->name }}
                            <br>
                        </span>
                        @if ($task->completedReview($nowFormated, auth()->user()->id)->count() > 0)
                        @php
                        $rw = $task->completedReview($nowFormated, auth()->user()->id)->first();
                        @endphp
                        <span class="text-amber-600 w-full font-semibold">
                            <span>
                                Validado por <b>{{$rw->user->name}}</b>

                                a las {{$rw->date}} {{ $rw->time }}

                                @if (isset($rw->validation->id))

                                indico el valor de <b>{{ $rw->validation->value ?? '---'}}</b>
                                @else
                                indico el valor de <b>{{ $rw->value ?? '---' }}</b>

                                @endif
                                y dijo {{ $rw->comments ?? '---' }}
                            </span>
                        </span>
                        @else
                        {{-- <input type="text" class="text-amber-700 rounded-lg px-3 py-2 w-full md:w-1/8 mt-2 md:mt-0"
                            name="comment-{{ $task->id }}" id="comment-{{ $task->id.'.'.$task->id }}"
                            placeholder="Comentario (opcional)" wire:model="comments.t-{{$task->id}}">
                        <div x-data="{ loading: false }" class="md:w-1/8 w-full">
                            <button
                                class="bg-amber-500 text-black rounded-lg px-3 py-2 flex flex-row gap-2 text-center items-center justify-center w-full"
                                x-on:click="loading = true; getLocationAndReview({{ $task->id }}, null)"
                                x-bind:disabled="loading" x-bind:class="{ 'opacity-50 cursor-not-allowed': loading }">
                                <span x-show="!loading">@include('icons.save') Comentario general</span>
                                <span x-show="loading">Obteniendo ubicación...</span>
                            </button>
                        </div> --}}
                        @endif
                        <span x-show="showingTask != '{{ $task->id }}'">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                            </svg>
                        </span>
                        <span x-show="showingTask == '{{ $task->id }}'">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                            </svg>
                        </span>
                    </li>
                    <ul class="space-y-2" x-show="showingTask == '{{ $task->id }}'">
                        @foreach ($task->subtasks as $st )
                        <li
                            class="flex flex-col gap-4 md:flex-row items-center justify-between p-4 bg-gray-50 dark:bg-slate-700 rounded-lg shadow-sm transition-transform transform hover:scale-[1.01] hover:shadow-md">
                            <span class="text-amber-500 ml-2 md:w-5/8 w-full">
                                <span class="font-bold">
                                    {{ $st->name }}
                                    <br>


                                </span>
                                <br>
                                <small>
                                    {{ $task->name }}
                                </small>
                            </span>
                            @if ($st->completedReview($nowFormated, auth()->user()->id, $task->id)->count() >
                            0)
                            @php
                            $rw = $st->completedReview($nowFormated, auth()->user()->id, $task->id)->first();
                            @endphp
                            <span class="text-green-600 w-full font-semibold">
                                <span>
                                    Validado por <b>{{$rw->user->name}}</b>

                                    a las {{$rw->date}} {{ $rw->time }}

                                    @if (isset($rw->validation->id))

                                    indico el valor de <b>{{ $rw->validation->value ?? '---'}}</b>
                                    @else
                                    indico el valor de <b>{{ $rw->value ?? '---' }}</b>

                                    @endif
                                    y dijo {{ $rw->comments ?? '---' }}
                                </span>
                            </span>
                            @else
                            @if ($st->validations->count() > 1)
                            <select class="text-amber-700 rounded-lg px-3 py-2 w-full md:w-1/8"
                                name="subtask-{{ $st->id }}" id="{{ $task->id.'.'.$st->id }}"
                                wire:model="validation_ids.st-{{$st->id}}">
                                <option value="">Seleciona</option>
                                @foreach ($st->validations as $v)
                                <option value="{{ $v->id }}">{{ $v->name }}</option>
                                @endforeach
                            </select>
                            @else
                            @if ($st->validations->count() > 0)
                            <input type="{{ $st->validations->first()->value }}"
                                class="text-amber-700 rounded-lg px-3 py-2 w-full md:w-1/8" name="subtask-{{ $st->id }}"
                                id="{{ $st->id.'.'.$st->id }}" wire:model="validationValues.st-{{$st->id}}"
                                placeholder="{{ $st->validations->first()->name }}">
                            @endif
                            @endif
                            <input type="text" class="text-amber-700 rounded-lg px-3 py-2 w-full md:w-1/8 mt-2 md:mt-0"
                                name="comment-{{ $st->id }}" id="comment-{{ $st->id.'.'.$st->id }}"
                                placeholder="Comentario (opcional)" wire:model="comments.st-{{$st->id}}">
                            <div class="flex flex-row gap-2 w-1/2 md:max-w-2/8">
                                <div x-data="{ loading: false }" class="w-1/2">
                                    <button class="bg-green-500 text-black rounded-lg text-xs px-3 py-2 w-full"
                                        x-on:click="loading = true; getLocationAndReview({{ $task->id }}, {{ $st->id }})"
                                        x-bind:disabled="loading"
                                        x-bind:class="{ 'opacity-50 cursor-not-allowed': loading }">
                                        <span x-show="!loading"
                                            class="flex flex-row gap-2 items-center justify-center">@include('icons.validate')
                                            Validar Tarea</span>
                                        <span x-show="loading">Obteniendo ubicación...</span>
                                    </button>
                                </div>
                                <a class=" bg-red-500 text-white rounded-lg px-3 text-xs py-2 flex flex-row gap-2 items-center justify-center w-1/2"
                                    href="{{route('failures', ['task_id' => $task->id, 'subtask_id' => $st->id])}}">
                                    @include('icons.cancel')
                                    Registrar Falla
                                </a>
                            </div>
                            @endif
                        </li>
                        @endforeach
                    </ul>
                    @endforeach

                    @if($groups->isEmpty() && $tasksWithoutGroup->isEmpty())
                    <li class="text-center text-gray-500 dark:text-gray-400">No hay tareas para mostrar.</li>
                    @endif
                </ul>
            </div>

        </div>
    </div>
    <script>
        function getLocationAndReview(taskId, subtaskId = null) {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const latitude = position.coords.latitude;
                        const longitude = position.coords.longitude;
                        console.log(`Ubicación obtenida: ${latitude}, ${longitude}`);

                        // 1. Establece las propiedades en Livewire
                        @this.set('latitude', latitude);
                        @this.set('longitude', longitude);

                        // 2. Llama al método de Livewire DESPUÉS de establecer la ubicación
                        @this.reviewTask(taskId, subtaskId);
                    },
                    (error) => {
                        if (error.code === error.PERMISSION_DENIED) {
                            alert('El permiso de ubicación es necesario para registrar tus tareas. Por favor, habilita la ubicación en tu navegador.');
                        } else {
                            alert('No se pudo obtener la ubicación. Se registrará sin coordenadas. Error: ' + error.message);
                        }
                        console.error('Error al obtener la ubicación:', error);
                        // Opcional: Registrar la tarea sin ubicación si falla
                        @this.reviewTask(taskId, subtaskId);
                    }
                );
            } else {
                alert('Tu navegador no soporta la geolocalización. No podrás registrar tus tareas.');
                // Opcional: Registrar la tarea sin ubicación si el navegador no es compatible
                @this.reviewTask(taskId, subtaskId);
            }
        }
    </script>
</div>
