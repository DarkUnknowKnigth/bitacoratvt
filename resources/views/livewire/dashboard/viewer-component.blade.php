<div class="flex flex-col gap-8">
    <style>
    </style>
    <!-- Tasks and simple progress section -->
    <div class="md:col-span-2 space-y-8">
        <!-- Task Progress Summary -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6 flex flex-col items-center justify-center transition-all duration-300 transform hover:scale-[1.01]">
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
    </div>
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Listado de Tareas</h2>
        <table class="table-auto m-auto md:max-w-xl w-full">
            @foreach ($tasks as $task)
                <tr>
                    <td>Tarea</td>
                    <td colspan="2">{{$task->name}}</td>
                    <td>
                        Sub tareas completadas
                        {{ $task->completedReview($nowFormated, auth()->user()->location_id)->count(), null}}/{{ $task->subtasks->count() }}
                    </td>
                </tr>
                <tr>
                    <td>Sub tarea</td>
                    <td>Validacion</td>
                    <td>Comentario</td>
                    <td>Fecha y hora</td>
                </tr>
                @foreach ($task->subtasks as $st)
                    <tr>
                        @php
                            $reviewQuery = $st->completedReview($nowFormated, auth()->user()->location_id, $task->id)->where('user_id',auth()->user()->id);
                        @endphp
                        <td>{{$st->name}}</td>
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
                                    <li>Sin captura</li>
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
                            @if ($reviewQuery->count() > 0)
                                {{ $reviewQuery->first()->date }} {{ $reviewQuery->first()->time }}
                            @else
                                Sin captura
                            @endif
                        </td>
                    </tr>
                @endforeach
            @endforeach
        </table>
    </div>
</div>
