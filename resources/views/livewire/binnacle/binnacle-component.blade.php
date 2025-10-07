<div class="min-h-screen dark:bg-slate-900 font-sans p-8 text-gray-900 dark:text-gray-100">
    <!-- Encabezado del Dashboard -->
    <div class="flex flex-col md:flex-row gap-2 items-center justify-between mb-8">
        <h1 class="text-3xl font-bold tracking-tight text-blue-800 dark:text-blue-400">Administrador de Bitácoras</h1>
        <div class="flex items-center gap-4">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar bitácora..."
                class="w-full md:w-auto rounded-lg px-3 py-2 text-blue-950 dark:bg-slate-700 dark:text-gray-100">
            @include('auth._auth')
        </div>
    </div>

    <!-- Contenedor Principal -->
    <div class="flex flex-col gap-8">
        <div class="md:col-span-2 space-y-8">
            <!-- Formulario de Creación/Edición -->
            <div
                class="bg-white px-5 dark:bg-slate-800 rounded-xl shadow-lg py-6 flex flex-col items-center justify-center transition-all duration-300 transform hover:scale-[1.01]">
                <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">{{ $binnacle_id ? 'Editar
                    Bitácora' : 'Crear Nueva Bitácora' }}</h2>
                @include('partials._validation')
                <div class="flex flex-col items-center justify-center text-center w-full">
                    <form wire:submit.prevent="{{ $binnacle_id ? 'update(' . $binnacle_id . ')' : 'save' }}"
                        class="w-full">
                        <div
                            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-5 items-center justify-center w-full">
                            <!-- Nombre -->
                            <div class="flex flex-col">
                                <label for="name" class="mb-1">Nombre:</label>
                                <input type="text" name="name" wire:model="name" id="name"
                                    class="w-full rounded-lg px-3 py-2 text-blue-950 dark:bg-slate-700 dark:text-gray-100">
                            </div>
                            <!-- Tipo -->
                            <div class="flex flex-col">
                                <label for="type" class="mb-1">Tipo:</label>
                                <select wire:model="type" id="type"
                                    class="w-full rounded-lg px-3 py-2 text-blue-950 dark:bg-slate-700 dark:text-gray-100">
                                    <option value="">Seleccione un tipo</option>
                                    <option value="location">Bitácora de sucursal</option>
                                    <option value="role">Bitácora para roles</option>
                                </select>
                            </div>
                            <!-- Ubicación -->
                            <div class="flex flex-col">
                                <label for="location_id" class="mb-1">Ubicación:</label>
                                <select wire:model="location_id" id="location_id"
                                    class="w-full rounded-lg px-3 py-2 text-blue-950 dark:bg-slate-700 dark:text-gray-100">
                                    <option value="">Ninguna</option>
                                    @foreach($locations as $location)
                                    <option value="{{ $location->id }}">{{ $location->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Rol -->
                            <div class="flex flex-col">
                                <label for="role_id" class="mb-1">Rol Asignado:</label>
                                <select wire:model="role_id" id="role_id"
                                    class="w-full rounded-lg px-3 py-2 text-blue-950 dark:bg-slate-700 dark:text-gray-100">
                                    <option value="">Ninguna</option>
                                    @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Botones -->
                            <div class="flex flex-col md:flex-row gap-2 mt-4">
                                <button type="submit"
                                    class="w-full text-white px-3 py-2 rounded-lg bg-amber-600 flex flex-row gap-2 items-center justify-center">@include('icons.save')
                                    Guardar</button>
                                @if($binnacle_id)
                                <button type="button" wire:click="resetFields"
                                    class="w-full text-white px-3 py-2 rounded-lg bg-gray-500 flex flex-row gap-2 items-center justify-center">Cancelar</button>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Lista de Bitácoras -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Listado de Bitácoras</h2>
                <ul class="space-y-4">
                    @forelse ($binnacles as $binnacle)
                    <li wire:key="{{ $binnacle->id }}"
                        class="flex flex-col md:flex-row items-center justify-between gap-4 p-4 bg-gray-50 dark:bg-slate-700 rounded-lg shadow-sm transition-transform transform hover:scale-[1.01] hover:shadow-md">
                        <div class="flex-1">
                            <p class="font-semibold text-lg">{{ $binnacle->name }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-300">
                                Tipo: <span class="font-medium capitalize">{{ $binnacle->type }}</span>
                                @if ($binnacle->type == 'location')
                                    Ubicación: <span class="font-medium">{{ $binnacle->location->name }}</span>
                                @endif
                                @if ($binnacle->type == 'role')
                                    Rol: <span class="font-medium">{{ $binnacle->role->name }}</span>
                                @endif
                            </p>
                        </div>
                        <div class="flex flex-col md:flex-row gap-2 w-full md:w-auto">
                            <button
                                class="bg-yellow-400 text-gray-900 rounded-lg flex flex-row gap-2 md:w-auto w-full items-center justify-center px-3 py-2"
                                wire:click="edit({{ $binnacle->id }})">@include('icons.edit') Editar</button>
                            <button
                                class="bg-red-500 text-white rounded-lg flex flex-row gap-2 md:w-auto w-full items-center justify-center px-3 py-2"
                                onclick="return confirm('¿Estás seguro de que quieres eliminar esta bitácora?') ? @this.call('destroy', {{ $binnacle->id }}) : false;">@include('icons.delete')
                                Eliminar</button>
                        </div>
                    </li>
                    @empty
                    <li class="text-center text-gray-500 dark:text-gray-400">No hay bitácoras para mostrar.</li>
                    @endforelse
                </ul>
                <div class="mt-4">
                    {{ $binnacles->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
</div>
