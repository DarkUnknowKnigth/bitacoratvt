<div class="min-h-screen dark:bg-slate-900 font-sans p-8 text-gray-900 dark:text-gray-100">
    <!-- Dashboard Header -->
    <div class="flex flex-col md:flex-row gap-2 items-center justify-between mb-8">
        <h1 class="text-3xl font-bold tracking-tight text-blue-800 dark:text-blue-400">Historial de bitácoras</h1>
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
                    <span class="flex flew-row gap-2 items-center justify-center">
                        Día <input type="date" wire:model="nowDate" wire:change="reloadReviews">
                    </span>
                    <span class="flex flew-row gap-2 items-center justify-center">
                        Bitácoras
                        <select name="binnacle_id" wire:model="binnacle_id" id="binnacle_id"
                            class="w-full md:w-auto rounded-lg px-3 py2 text-blue-950" wire:change="reloadReviews">
                            <option value="">Todos</option>
                            @foreach ($binnacles as $binnacle)
                            <option value="{{ $binnacle->id }}">{{ $binnacle->name }}</option>
                            @endforeach
                        </select>
                    </span>
                    <span class="flex flew-row gap-2 items-center justify-center">
                        Usuarios
                        <select name="user_id" wire:model="user_id" id="user_id"
                            class="w-full md:w-auto rounded-lg px-3 py2 text-blue-950" wire:change="reloadReviews">
                            <option value="">Todos</option>
                            @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </span>
                    <button class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors duration-300" wire:click="export()">Exportar</button>
                </h2>
            </div>
            <div class="grid md:grid-cols-2 grid-cols-1 gap-8">
                {{-- //crea un grafico en base al desempleño de las tareas por y hora te pasare una query que contenga
                la
                hora y las tareas completas y lo debes de graficar con chartjs el eje x sera las horas del dia y el eje
                y las tareas completadas --}}
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Rendimiento por hora</h2>
                    <canvas id="performanceChart" style="max-height: 500px;"></canvas>
                </div>
                @if (auth()->user()->roles->pluck('slug')->contains('admin'))
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Rendimiento por Sucursal</h2>
                    <canvas id="locationChart" style="max-height: 500px;"></canvas>
                </div>
                @endif

                @script
                <script>
                    document.addEventListener('livewire:initialized', async () => {
                    const ctx = document.getElementById('performanceChart').getContext('2d');
                    let performanceChart;
                    const ctxLocation = document.getElementById('locationChart')?.getContext('2d');
                    let locationChart;

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

                    const renderLocationChart = (dataframe) => {
                        if (!ctxLocation) return;
                        if (locationChart) {
                            locationChart.destroy();
                        }

                        locationChart = new Chart(ctxLocation, {
                            type: 'pie', // O 'bar' si lo prefieres
                            data: {
                                labels: dataframe.map(item => item.location_name),
                                datasets: [{
                                    label: 'Tareas por Sucursal',
                                    data: dataframe.map(item => item.total),
                                    backgroundColor: [
                                        'rgba(255, 99, 132, 0.6)',
                                        'rgba(54, 162, 235, 0.6)',
                                        'rgba(255, 206, 86, 0.6)',
                                        'rgba(75, 192, 192, 0.6)',
                                        'rgba(153, 102, 255, 0.6)',
                                        'rgba(255, 159, 64, 0.6)'
                                    ],
                                    borderColor: [
                                        'rgba(255, 99, 132, 1)',
                                        'rgba(54, 162, 235, 1)',
                                        'rgba(255, 206, 86, 1)',
                                        'rgba(75, 192, 192, 1)',
                                        'rgba(153, 102, 255, 1)',
                                        'rgba(255, 159, 64, 1)'
                                    ],
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        position: 'top',
                                    }
                                }
                            }
                        });
                    };

                    const dataframe = await @this.call('getHourlyPerformance');
                    renderChart(dataframe);
                    const locationDataframe = await @this.call('getPerformanceByLocation');
                    renderLocationChart(locationDataframe);
                    @this.on('update-chart', ({
                        data
                    }) => renderChart(data));
                    @this.on('update-location-chart', ({ data }) => renderLocationChart(data));
                });
                </script>
                @endscript

            </div>
            <!-- Task List -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Listado de bitácoras</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">Fecha y Hora</th>
                                <th scope="col" class="px-6 py-3">Tipo</th>
                                <th scope="col" class="px-6 py-3">Tarea</th>
                                <th scope="col" class="px-6 py-3">Usuario</th>
                                <th scope="col" class="px-6 py-3">Sucursal</th>
                                <th scope="col" class="px-6 py-3">Valor</th>
                                <th scope="col" class="px-6 py-3">Comentario</th>
                                <th scope="col" class="px-6 py-3">Ubicación</th>
                                <th scope="col" class="px-6 py-3">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reviews as $review)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($review->date)->format('d/m/Y') }} {{ $review->time }}</td>
                                    <td class="px-6 py-4">
                                        @if ($review->subtask_id && isset($review->subtask->id))
                                            Subtarea
                                        @else
                                            Principal
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                        @if ($review->subtask_id && isset($review->subtask->id))
                                            {{ $review->subtask->name }}
                                        @elseif ($review->task_id && isset($review->task->id))
                                            {{ $review->task->name }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">{{ $review->user->name }}</td>
                                    <td class="px-6 py-4">{{ $review->location->name }}</td>
                                    <td class="px-6 py-4">
                                        @if ($review->validation_id)
                                            {{ $review->validation->value }}
                                        @else
                                            {{ $review->value ?? 'N/A' }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">{{ $review->comments }}</td>
                                    <td class="px-6 py-4">
                                        {{ $review->latitude }}, {{ $review->longitude }}
                                        <br>
                                        <a href="{{ route('map',['user'=> $review->user->id, 'date'=> $nowDate]) }}">Ver ubicación</a>
                                    </td>
                                    <td class="px-6 py-4">
                                        <button class="px-3 py-2 bg-red-500 text-white rounded-lg flex items-center justify-center gap-2" wire:click="destroy({{ $review->id }})">
                                            @include('icons.delete') Eliminar
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-gray-500 dark:text-gray-400">No hay bitácora para mostrar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Listado de Fallas -->
            @if ($failures->count() > 0)
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Listado de Fallas Reportadas</h2>
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
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($failures as $failure)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($failure->date)->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4">{{ $failure->task->name }} {{ $failure->subtask ? '-> '.$failure->subtask->name : '' }}</td>
                                    <td class="px-6 py-4">{{ $failure->location->name }}</td>
                                    <td class="px-6 py-4 max-w-xs truncate" title="{{ $failure->description }}">{{ $failure->description }}</td>
                                    <td class="px-6 py-4">{{ $failure->user->name }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $failure->solved ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                                            {{ $failure->solved ? 'Resuelta' : 'Pendiente' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
