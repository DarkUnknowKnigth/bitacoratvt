<div class="flex flex-col gap-8">
    <style>
    </style>
    <!-- Tasks and simple progress section -->
    <div class="md:col-span-2 space-y-8">
        <!-- Task Progress Summary -->
        @if (auth()->user()->isAdmin())
        <div
            class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6 flex flex-col items-center justify-center transition-all duration-300 transform hover:scale-[1.01]">
            <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Bitácora por sucursal</h2>
            <div class="flex md:flex-row flex-col gap-5 items-center justify-center">
                <div class="flex flex-col items-center justify-center text-center">
                    <label for="location">Sucursal</label>
                    <select class="w-full px-3 py-2" name="location" id="location" wire:model.live="selectedLocation">
                        @if (auth()->user()->role->slug==='admin')
                        <option value="">Todas</option>
                        @endif
                        @foreach ($locations as $location)
                        <option value="{{ $location->id }}">{{ $location->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex flex-col items-center justify-center text-center">
                    <label for="user">Técnico</label>
                    <select name="user" id="user" wire:model.live="selectedUser">
                        @if (auth()->user()->role->slug==='admin')
                        <option value="">Todos</option>
                        @endif
                        @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex flex-col items-center justify-center text-center">
                    <label for="date">Fecha</label>
                    <input type="date" id="date" wire:model.live="selectedDate">
                </div>
            </div>
        </div>
        @else
        <div
            class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6 flex flex-col items-center justify-center transition-all duration-300 transform hover:scale-[1.01]">
            Técnico {{ auth()->user()->name }} de la sucursal {{ auth()->user()->location->name }}
        </div>
        @endif
    </div>
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6 overflow-x-scroll w-full max-w-7xl mx-auto">
        <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Listado de Tareas</h2>
        <table class="table-auto m-auto w-full text-left">
            {{-- Iterar sobre los grupos --}}
            @foreach ($groups as $group)
            @if($group->tasks->count() > 0)
            <thead class="bg-blue-100 dark:bg-blue-900 rounded-lg">
                <tr>
                    <th colspan="6" class="px-4 py-3 text-lg font-bold text-blue-800 dark:text-blue-300">
                        {{ $group->name }}
                    </th>
                </tr>
            </thead>
            @endif

            @foreach ($group->tasks as $task)
            {{-- Fila de la Tarea Principal --}}
            <tr class="text-blue-900 dark:text-blue-200">
                <td>Tarea</td>
                <td colspan="2">
                    {{$task->name}}
                    <br>
                </td>
                <td>
                    Subtareas completadas
                    {{ $task->completedSubtasks($nowFormated, auth()->user()->location_id, auth()->user())->count() }} /
                    {{ $task->subtasks->count() }}
                </td>
                <td>
                    @php
                    $taskReview = $task->completedReview($nowFormated, auth()->user()->location_id)->first();
                    @endphp
                    {{ $taskReview->comments ?? 'Sin comentarios adicionales' }}
                </td>
            </tr>
            {{-- Encabezados de Subtareas --}}
            <tr class="text-amber-900 dark:text-amber-200">
                <td>Subtareas</td>
                <td>Previa Val. <br> {{$prevDate}}</td>
                <td>Validación {{$nowFormated}}</td>
                <td>Comentario</td>
                <td>Fallas</td>
                <td>Ubicación, fecha y hora</td>
            </tr>
            {{-- Iterar sobre las Subtareas --}}
            @foreach ($task->subtasks->sortBy('name') as $st)
            <tr>
                @php
                $reviewQuery = $st->completedReview($nowFormated, auth()->user()->location_id, $task->id);
                $prevReviewQuery = $st->completedReview($prevDate, auth()->user()->location_id, $task->id);
                @endphp
                <td>
                    {{$st->name}}
                    <br>

                </td>
                <td>
                    <ul>
                        @forelse ($prevReviewQuery->get() as $review)
                        @if ($review->validation)
                        <li class="flex items-center gap-2">
                            {!! $review->validation->icon !!}
                            <span>{{$review->validation->value}}</span>
                        </li>
                        @else
                        {{$review->value}}
                        @endif
                        @empty
                        <li class="flex items-center gap-2 text-amber-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                            </svg>
                            <small>Sin captura</small>
                        </li>
                        @endforelse
                    </ul>
                </td>
                <td>
                    <ul>
                        @forelse ($reviewQuery->get() as $review)
                        @if ($review->validation)
                        <li class="flex items-center gap-2">
                            {!! $review->validation->icon !!}
                            <span>{{$review->validation->value}}</span>
                        </li>
                        @else
                        {{$review->value}}
                        @endif
                        @empty
                        <li class="flex items-center gap-2 text-amber-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                            </svg>
                            <small>Sin captura</small>
                        </li>
                        @endforelse
                    </ul>
                </td>
                <td>
                    @if ($reviewQuery->count() > 0)
                    {{ $reviewQuery->first()->commnets }}
                    @else
                    Sin captura
                    @endif
                </td>
                <td>
                    <ul>
                        @forelse ($st->subtaskFailures as $failure)
                        <li class="mb-1">
                            <span title="{{$failure->description}}"
                                class="flex items-center justify-left gap-2 p-1 text-xs font-semibold {{ $failure->solved ? 'text-green-500' : 'text-red-500' }}">
                                <span
                                    class="px-2 py-1 rounded-full {{ $failure->solved ? 'bg-green-600 text-white' : 'bg-red-700 text-white' }}">
                                    @if ($failure->solved) Solucionado @else Sin resolver @endif
                                </span>
                                <span class="truncate max-w-xs">{{ $failure->description }}</span>
                            </span>
                        </li>
                        @empty
                        <span class="text-gray-400">Sin fallas</span>
                        @endforelse
                    </ul>
                </td>
                <td>
                    @if ($reviewQuery->count() > 0)
                    <a href="{{ route('map',['user'=>$reviewQuery->first()->user->id, 'date'=>  $reviewQuery->first()->date]) }}">
                        @include('icons.map') {{ $reviewQuery->first()->date }} {{ $reviewQuery->first()->time }}
                    </a>
                    @else
                    <div class="flex items-center gap-2 text-amber-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                        </svg>
                        <small>Faltante</small>
                    </div>
                    @endif
                </td>
            </tr>
            @endforeach
            @endforeach
            @endforeach

            {{-- Iterar sobre las tareas sin grupo --}}
            @if($tasksWithoutGroup->count() > 0)
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th colspan="6" class="px-4 py-3 text-lg font-bold text-gray-800 dark:text-gray-300">
                        Otras Tareas (Sin Grupo)
                    </th>
                </tr>
            </thead>
            @endif

            @foreach ($tasksWithoutGroup as $task)
            <tr class="text-blue-900 dark:text-blue-200">
                <td>Tarea</td>
                <td colspan="2">
                    {{$task->name}}
                    <br>

                </td>
                <td>
                    Subtareas completadas
                    {{ $task->completedSubtasks($nowFormated, auth()->user()->location_id, auth()->user())->count() }} /
                    {{ $task->subtasks->count() }}
                </td>
                <td>
                    @php
                    $taskReview = $task->completedReview($nowFormated, auth()->user()->location_id)->first();
                    @endphp
                    {{ $taskReview->comments ?? 'Sin comentarios adicionales' }}
                </td>
            </tr>
            <tr class="text-amber-900 dark:text-amber-200">
                <td>Subtareas</td>
                <td>Previa Val. <br> {{$prevDate}}</td>
                <td>Validación {{$nowFormated}}</td>
                <td>Comentario</td>
                <td>Fallas</td>
                <td>Ubicación, fecha y hora</td>
            </tr>
            @foreach ($task->subtasks->sortBy('name') as $st)
            <tr>
                @php
                $reviewQuery = $st->completedReview($nowFormated, auth()->user()->location_id, $task->id);
                $prevReviewQuery = $st->completedReview($prevDate, auth()->user()->location_id, $task->id);
                @endphp
                <td>
                    {{$st->name}}
                    <br>

                </td>
                <td>
                    <ul>
                        @forelse ($prevReviewQuery->get() as $review)
                        @if ($review->validation)
                        <li class="flex items-center gap-2">
                            {!! $review->validation->icon !!}
                            <span>{{$review->validation->value}}</span>
                        </li>
                        @else
                        {{$review->value}}
                        @endif
                        @empty
                        <li class="flex items-center gap-2 text-amber-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                            </svg>
                            <small>Sin captura</small>
                        </li>
                        @endforelse
                    </ul>
                </td>
                <td>
                    <ul>
                        @forelse ($reviewQuery->get() as $review)
                        @if ($review->validation)
                        <li class="flex items-center gap-2">
                            {!! $review->validation->icon !!}
                            <span>{{$review->validation->value}}</span>
                        </li>
                        @else
                        {{$review->value}}
                        @endif
                        @empty
                        <li class="flex items-center gap-2 text-amber-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                            </svg>
                            <small>Sin captura</small>
                        </li>
                        @endforelse
                    </ul>
                </td>
                <td>
                    @if ($reviewQuery->count() > 0)
                    {{ $reviewQuery->first()->commnets }}
                    @else
                    Sin captura
                    @endif
                </td>
                <td>
                    <ul>
                        @forelse ($st->subtaskFailures as $failure)
                        <li class="mb-1">
                            <span title="{{$failure->description}}"
                                class="flex items-center justify-left gap-2 p-1 text-xs font-semibold {{ $failure->solved ? 'text-green-500' : 'text-red-500' }}">
                                <span
                                    class="px-2 py-1 rounded-full {{ $failure->solved ? 'bg-green-600 text-white' : 'bg-red-700 text-white' }}">
                                    @if ($failure->solved) Solucionado @else Sin resolver @endif
                                </span>
                                <span class="truncate max-w-xs">{{ $failure->description }}</span>
                            </span>
                        </li>
                        @empty
                        <span class="text-gray-400">Sin fallas</span>
                        @endforelse
                    </ul>
                </td>
                <td>
                    @if ($reviewQuery->count() > 0)
                    <a href="{{ route('map',['user'=>$reviewQuery->first()->user->id, 'date'=>  $reviewQuery->first()->date]) }}">
                        @include('icons.map')
                        {{ $reviewQuery->first()->date }} {{ $reviewQuery->first()->time }}
                    </a>
                    @else
                    <div class="flex items-center gap-2 text-amber-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                        </svg>
                        <small>Faltante</small>
                    </div>
                    @endif
                </td>
            </tr>
            @endforeach
            @endforeach
        </table>
    </div>
</div>
