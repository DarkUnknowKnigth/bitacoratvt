<div class="min-h-screen dark:bg-slate-900 font-sans p-8 text-gray-900 dark:text-gray-100">
    <!-- Dashboard Header -->
    <div class="flex flex-col md:flex-row gap-2 items-center justify-between mb-8">
        <h1 class="text-3xl font-bold tracking-tight text-blue-800 dark:text-blue-400">Administrador de sucursales</h1>
        <div class="flex space-x-4">
            <!-- Button to create location -->
            @include('auth._auth')
        </div>
    </div>

    <!-- Main Container -->
    <div class="flex flex-col gap-8">
        <!-- locations and simple progress section -->
        <div class="md:col-span-2 space-y-8">
            <!-- location Progress Summary -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg py-6 flex flex-col items-center justify-center transition-all duration-300 transform hover:scale-[1.01]">
                <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Sucursales</h2>
                @include('partials._validation')
                <div class="flex flex-col items-center justify-center text-center">
                    <form  wire:submit="{{$location_id?'update('.$location_id.')':'save()'}}" method="post">
                        <div class="md:grid md:grid-cols-2 flex flex-col gap-5 items-center justify-center w-full">
                                <label for="name">
                                    Nombre:
                                </label>
                                <input type="text" name="name" wire:model="name" id="name" class="w-full md:w-auto rounded-lg px-3 py2 text-blue-950">
                                <label for="address">
                                    Dirección:
                                </label>
                                <input type="text" name="address" wire:model="address" id="address" class="w-full md:w-auto rounded-lg px-3 py2 text-blue-950">
                            <button type="submit" class="col-span-2 w-full text-white md:w-auto px-3 py-2 rounded-lg bg-amber-600 flex flex-row gap-2 items-center justify-center">@include('icons.save') Guardar</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- location List -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Listado de sucursales</h2>
                <ul class="space-y-4">
                    @forelse ($locations as $location)
                        <li class="flex flex-col gap-4 md:flex-row items-center justify-between p-4 bg-gray-50 dark:bg-slate-700 rounded-lg shadow-sm transition-transform transform hover:scale-[1.01] hover:shadow-md">
                            <span class="md:w-3/4 w-full">{{ $location->name }}  <br> {{$location->address}}</span>
                            <button class="px-3 py-2 bg-yellow-400 text-gray-900 rounded-lg flex flex-row gap-2 md:w-auto w-full items-center justify-center" wire:click="edit({{ $location->id }}); document.getElementById('name').focus()">@include('icons.edit') Editar</button>
                            <button class="px-3 py-2 bg-red-500 text-white rounded-lg flex flex-row gap-2 md:w-auto w-full items-center justify-center" onclick="return confirm('¿Estás seguro de que quieres eliminar este elemento?') ? @this.call('destroy', {{ $location->id }}) : false;">@include('icons.delete') Eliminar</button>
                        </li>
                    @empty
                        <li class="text-center text-gray-500 dark:text-gray-400">No hay sucursales para mostrar.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>

