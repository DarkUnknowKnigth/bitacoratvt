<div class="min-h-screen dark:bg-slate-900 font-sans p-8 text-gray-900 dark:text-gray-100">
    <!-- Dashboard Header -->
    <div class="flex flex-col md:flex-row gap-2 items-center justify-between mb-8">
        <h1 class="text-3xl font-bold tracking-tight text-blue-800 dark:text-blue-400">Administrador de bitácoras</h1>
        <div class="flex space-x-4">
            <!-- Button to create review -->
            @include('auth._auth')
        </div>
    </div>

    <!-- Main Container -->
    <div class="flex flex-col gap-8">
        <!-- Tasks and simple progress section -->
        <div class="md:col-span-2 space-y-8">
            <!-- Task Progress Summary -->
            <div
                class="bg-white px-5 dark:bg-slate-800 rounded-xl shadow-lg py-6 flex flex-col items-center justify-center transition-all duration-300 transform hover:scale-[1.01]">
                <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200 flex md:flex-row flex-col gap-5">
                    <span>
                        Día <input type="date" wire:model="nowDate" wire:change="reloadReviews">
                    </span>
                    <span>
                        Sucursal
                        @if (auth()->user()->role->slug=='admin')
                        <select name="location_id" wire:model="location_id" id="location_id"
                            class="w-full md:w-auto rounded-lg px-3 py2 text-blue-950" wire:change="reloadReviews">
                            <option value="">Todos</option>
                            @foreach ($locations as $l)
                            <option value="{{ $l->id }}">{{ $l->name }}</option>
                            @endforeach
                        </select>
                        @else
                        {{ auth()->user()->location->name }}
                        @endif
                    </span>
                    <span>
                        Usuarios
                        <select name="user_id" wire:model="user_id" id="user_id"
                            class="w-full md:w-auto rounded-lg px-3 py2 text-blue-950" wire:change="reloadReviews">
                            <option value="">Todos</option>
                            @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </span>
                </h2>
            </div>
            <div>
                {{-- //crea un grafico en base al desempleño de las tareas por y hora te pasare una query que contenga
                la
                hora y las tareas completas y lo debes de graficar con chartjs el eje x sera las horas del dia y el eje
                y las tareas completadas --}}
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6 max-w-xl mx-auto">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Rendimiento por hora</h2>
                    <canvas id="performanceChart"></canvas>
                </div>

                @script
                <script>
                    document.addEventListener('livewire:initialized', async () => {
                    const ctx = document.getElementById('performanceChart').getContext('2d');
                    let performanceChart;

                    const renderChart = (dataframe) => {
                        if (performanceChart) {
                            performanceChart.destroy();
                        }

                        performanceChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: dataframe.map(item => `${item.time}`),
                                datasets: [{
                                    label: 'Tareas completadas',
                                    data: dataframe.map(item => item.total),
                                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                                    borderColor: 'rgba(75, 192, 192, 1)',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                scales: {
                                    x: {
                                        title: {
                                            display: true,
                                            text: 'Hora del día'
                                        }
                                    },
                                    y: {
                                        beginAtZero: true,
                                        title: {
                                            display: true,
                                            text: 'Número de tareas completadas'
                                        },
                                        ticks: {
                                            precision: 0
                                        }
                                    }
                                }
                            }
                        });
                    };

                    const dataframe = await @this.call('getHourlyPerformance');
                    renderChart(dataframe);

                    @this.on('update-chart', ({
                        data
                    }) => renderChart(data));
                });
                </script>
                @endscript

            </div>
            <!-- Task List -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Listado de bitácoras</h2>
                <div class="space-y-4 overflow-x-scroll w-full">
                    <table class="table w-full text-center">
                        <tr>
                            <th>id</th>
                            <th>Fecha y hora</th>
                            <th>Tipo</th>
                            <th>Tarea</th>
                            <th>Sucursal</th>
                            <th>Valor</th>
                            <th>Comentario</th>
                            <th>#</th>
                        </tr>
                        @forelse($reviews as $review)
                        <tr>
                            <td>{{$review->id}}</td>
                            <td>{{$review->date}} <br> {{$review->time}}</td>
                            <td>
                                @if ($review->task_id && isset($review->task->id))
                                Principal
                                @endif
                                @if ($review->subtask_id && isset($review->subtask->id))
                                Subtarea
                                @endif
                            </td>
                            <td>
                                @if ($review->task_id && isset($review->task->id))
                                {{ $review->task->name }}
                                @endif
                                @if ($review->subtask_id && isset($review->subtask->id))
                                {{ $review->subtask->name }}
                                @endif
                            </td>
                            <td>
                                {{ $review->location->name }}
                            </td>
                            <td>
                                @if ($review->validation_id)
                                {{ $review->validation->value }}
                                @else
                                {{ $review->value }}
                                @endif
                            </td>
                            <td>{{$review->comments}}</td>
                            <td>
                                <button class="flex gap-5 bg-red-600 w-full text-center items-center justify-center"
                                    wire:click="destroy({{ $review->id }})">
                                    @include('icons.delete') Eliminar
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr class="text-center text-gray-500 dark:text-gray-400">
                            <td colspan="6">
                                No hay bitácora para mostrar.
                            </td>
                        </tr>
                        @endforelse
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
