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
    <div class="flex flex-col gap-8">
        <!-- Tasks and simple progress section -->
        <div class="md:col-span-2 space-y-8">
            <!-- Task Progress Summary -->
            <div
                class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6 flex flex-col items-center justify-center transition-all duration-300 transform hover:scale-[1.01]">
                <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Avance de Tareas</h2>
                <div class="flex flex-col items-center justify-center text-center">
                    <span class="text-3xl font-bold text-blue-700 dark:text-blue-400">{{ $completedTasksCount }} /
                        {{$allTasks}}</span>
                </div>
            </div>

            <!-- Task List -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6">
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
                    <li
                        class="flex md:flex-row flex-col gap-2 items-center justify-between p-4 bg-orange-50 dark:bg-blue-950 rounded-lg shadow-sm transition-transform transform hover:scale-[1.01] hover:shadow-md">
                        <span class="md:w-3/4 font-bold text-lg">
                            {{ $task->name }}
                            <br>
                            @forelse ($task->locations as $location)
                                <span class="text-xs bg-blue-200 text-blue-800 dark:bg-blue-700 dark:text-blue-200 px-2 py-1 rounded-full">{{ $location->name }}</span>
                            @empty
                                <span class="text-xs bg-gray-200 text-gray-800 dark:bg-gray-600 dark:text-gray-200 px-2 py-1 rounded-full">Global</span>
                            @endforelse
                        </span>
                        @if ($task->completedReview($nowFormated, auth()->user()->location_id)->count() > 0)
                        @php
                        $rw = $task->completedReview($nowFormated, auth()->user()->location_id)->first();
                        @endphp
                        <span class="text-amber-600 w-full flex gap-5 font-semibold">
                            <span>
                                Validado por <b>{{$rw->user->name}}</b>

                                a las {{$rw->date}} {{ $rw->time }}

                                @if (isset($rw->validation->id))

                                    indico el valor de <b>{{ $rw->validation->value  ?? '---'}}</b>
                                @else
                                    indico el valor de <b>{{ $rw->value ?? '---' }}</b>

                                @endif
                                y dijo {{ $rw->comments ?? '---' }}
                            </span>
                        </span>
                        @else
                        <input type="text" class="text-amber-700 rounded-lg px-3 py-2 w-full md:w-1/8 mt-2 md:mt-0"
                            name="comment-{{ $task->id }}" id="comment-{{ $task->id.'.'.$task->id }}"
                            placeholder="Comentario (opcional)" wire:model="comments.t-{{$task->id}}">
                        <button
                            class="bg-amber-500 text-black rounded-lg px-3 py-2 flex flex-row gap-2 md:w-1/8 text-center items-center justify-center w-full"
                            wire:click="reviewTask({{ $task->id }})">
                            @include('icons.save')
                            Comentario general
                        </button>
                        @endif
                    </li>
                    <ul class="space-y-2">
                        @foreach ($task->subtasks as $st )
                        <li
                            class="flex flex-col gap-4 md:flex-row items-center justify-between p-4 bg-gray-50 dark:bg-slate-700 rounded-lg shadow-sm transition-transform transform hover:scale-[1.01] hover:shadow-md">
                            <span class="text-amber-500 ml-2 md:w-5/8 w-full">
                                <span class="font-bold">
                                    {{ $st->name }}
                                    <br>
                                    @forelse ($st->locations as $location)
                                        <span class="text-xs bg-blue-200 text-blue-800 dark:bg-blue-700 dark:text-blue-200 px-2 py-1 rounded-full">{{ $location->name }}</span>
                                    @empty
                                        <span class="text-xs bg-gray-200 text-gray-800 dark:bg-gray-600 dark:text-gray-200 px-2 py-1 rounded-full">Global</span>
                                    @endforelse

                                </span>
                                <br>
                                <small>
                                    {{ $task->name }}
                                </small>
                            </span>
                            @if ($st->completedReview($nowFormated, auth()->user()->location_id, $task->id)->count() >
                            0)
                            @php
                            $rw = $st->completedReview($nowFormated, auth()->user()->location_id, $task->id)->first();
                            @endphp
                            <span class="text-green-600 w-full font-semibold">
                                <span>
                                    Validado por <b>{{$rw->user->name}}</b>

                                    a las {{$rw->date}} {{ $rw->time }}

                                    @if (isset($rw->validation->id))

                                        indico el valor de <b>{{ $rw->validation->value  ?? '---'}}</b>
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
                            <button
                                class=" bg-amber-500 text-black rounded-lg px-3 py-2 flex flex-row gap-2 md:w-auto items-center justify-center w-full"
                                wire:click="reviewTask({{ $task->id }},{{ $st->id }})">
                                @include('icons.validate')
                                Validar
                            </button>
                            <a
                                class=" bg-red-500 text-white rounded-lg px-3 py-2 flex flex-row gap-2 md:w-auto items-center justify-center w-full"
                                href="{{route('failures', ['task_id' => $task->id, 'subtask_id' => $st->id])}}"
                            >
                                @include('icons.cancel')
                                Falla
                            </a>
                            @endif
                        </li>
                        @endforeach
                    </ul>
                    @endforeach
                    @endforeach

                    {{-- Iterar sobre las tareas sin grupo --}}
                    @if($tasksWithoutGroup->count() > 0)
                    <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-2 my-4">
                        <h3 class="px-4 py-2 text-lg font-bold text-gray-800 dark:text-gray-300">
                            Otras Tareas (Sin Grupo)
                        </h3>
                    </div>
                    @endif

                    @foreach ($tasksWithoutGroup as $task)
                    {{-- Lógica de la Tarea --}}
                    <li
                        class="flex md:flex-row flex-col gap-2 items-center justify-between p-4 bg-orange-50 dark:bg-blue-950 rounded-lg shadow-sm transition-transform transform hover:scale-[1.01] hover:shadow-md">
                        <span class="md:w-3/4 font-bold text-lg">{{ $task->name }}
                        <br>
                            @forelse ($task->locations as $location)
                                <span class="text-xs bg-blue-200 text-blue-800 dark:bg-blue-700 dark:text-blue-200 px-2 py-1 rounded-full">{{ $location->name }}</span>
                            @empty
                                <span class="text-xs bg-gray-200 text-gray-800 dark:bg-gray-600 dark:text-gray-200 px-2 py-1 rounded-full">Global</span>
                            @endforelse

                        </span>
                        @if ($task->completedReview($nowFormated, auth()->user()->location_id)->count() > 0)
                        @php
                        $rw = $task->completedReview($nowFormated, auth()->user()->location_id)->first();
                        @endphp
                        <span class="text-amber-600 w-full font-semibold">
                            <span>
                                Validado por <b>{{$rw->user->name}}</b>

                                a las {{$rw->date}} {{ $rw->time }}

                                @if (isset($rw->validation->id))

                                    indico el valor de <b>{{ $rw->validation->value  ?? '---'}}</b>
                                @else
                                    indico el valor de <b>{{ $rw->value ?? '---' }}</b>

                                @endif
                                y dijo {{ $rw->comments ?? '---' }}
                            </span>
                        </span>
                        @else
                        <input type="text" class="text-amber-700 rounded-lg px-3 py-2 w-full md:w-1/8 mt-2 md:mt-0"
                            name="comment-{{ $task->id }}" id="comment-{{ $task->id.'.'.$task->id }}"
                            placeholder="Comentario (opcional)" wire:model="comments.t-{{$task->id}}">
                        <button
                            class="bg-amber-500 text-black rounded-lg px-3 py-2 flex flex-row gap-2 md:w-1/8 text-center items-center justify-center w-full"
                            wire:click="reviewTask({{ $task->id }})">
                            @include('icons.save')
                            Comentario general
                        </button>
                        @endif
                    </li>
                    <ul class="space-y-2">
                        @foreach ($task->subtasks as $st )
                        <li
                            class="flex flex-col gap-4 md:flex-row items-center justify-between p-4 bg-gray-50 dark:bg-slate-700 rounded-lg shadow-sm transition-transform transform hover:scale-[1.01] hover:shadow-md">
                            <span class="text-amber-500 ml-2 md:w-5/8 w-full">
                                <span class="font-bold">
                                    {{ $st->name }}
                                    <br>
                                    @forelse ($st->locations as $location)
                                        <span class="text-xs bg-blue-200 text-blue-800 dark:bg-blue-700 dark:text-blue-200 px-2 py-1 rounded-full">{{ $location->name }}</span>
                                    @empty
                                        <span class="text-xs bg-gray-200 text-gray-800 dark:bg-gray-600 dark:text-gray-200 px-2 py-1 rounded-full">Global</span>
                                    @endforelse

                                </span>
                                <br>
                                <small>
                                    {{ $task->name }}
                                </small>
                            </span>
                            @if ($st->completedReview($nowFormated, auth()->user()->location_id, $task->id)->count() >
                            0)
                            @php
                            $rw = $st->completedReview($nowFormated, auth()->user()->location_id, $task->id)->first();
                            @endphp
                            <span class="text-green-600 w-full font-semibold">
                                <span>
                                    Validado por <b>{{$rw->user->name}}</b>

                                    a las {{$rw->date}} {{ $rw->time }}

                                    @if (isset($rw->validation->id))

                                        indico el valor de <b>{{ $rw->validation->value  ?? '---'}}</b>
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
                            <button
                                class=" bg-amber-500 text-black rounded-lg px-3 py-2 flex flex-row gap-2 md:w-auto items-center justify-center w-full"
                                wire:click="reviewTask({{ $task->id }},{{ $st->id }})">
                                @include('icons.validate')
                                Validar
                            </button>
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
</div>
