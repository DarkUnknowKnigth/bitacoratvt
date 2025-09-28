<div class="min-h-screen dark:bg-slate-900 font-sans p-8 text-gray-900 dark:text-gray-100">
    <!-- Dashboard Header -->
    <div class="flex flex-col md:flex-row gap-2 items-center justify-between mb-8">
        <h1 class="text-3xl font-bold tracking-tight text-blue-800 dark:text-blue-400">Administrador de módulos</h1>
        <div class="flex space-x-4">
            <!-- Button to create module -->
            @include('auth._auth')
        </div>
    </div>

    <!-- Main Container -->
    <div class="flex flex-col gap-8">
        <!-- modules and simple progress section -->
        <div class="md:col-span-2 space-y-8">
            <!-- module Progress Summary -->
            <div class="bg-white px-5 dark:bg-slate-800 rounded-xl shadow-lg py-6 flex flex-col items-center justify-center transition-all duration-300 transform hover:scale-[1.01]">
                <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Módulos</h2>
                @include('partials._validation')
                <div class="flex flex-col items-center justify-center text-center">
                    <form wire:submit="{{$module_id?'update('.$module_id.')':'save()'}}" method="post">
                        <div class="md:grid md:grid-cols-4 flex flex-col gap-5 items-center justify-center w-full">
                            <label for="name">
                                Nombre:
                            </label>
                            <input type="text" name="name" wire:model="name" id="name" class="w-full md:w-auto rounded-lg px-3 py2 text-blue-950">
                            <label for="url">
                                URL:
                            </label>
                            <input type="text" name="url" wire:model="url" id="url" class="w-full md:w-auto rounded-lg px-3 py2 text-blue-950">
                            <label for="description">
                                Descripción:
                            </label>
                            <input type="text" name="description" wire:model="description" id="description" class="w-full md:w-auto rounded-lg px-3 py2 text-blue-950">
                            <button type="submit" class="col-span-2 w-full text-white md:w-auto px-3 py-2 rounded-lg bg-amber-600 flex flex-row gap-2 items-center justify-center">@include('icons.save') Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- module List -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Listado de módulos</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">Nombre</th>
                                <th scope="col" class="px-6 py-3">URL</th>
                                <th scope="col" class="px-6 py-3">Descripción</th>
                                <th scope="col" class="px-6 py-3">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($modules as $module)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">{{ $module->name }}</td>
                                    <td class="px-6 py-4">{{ $module->url }}</td>
                                    <td class="px-6 py-4">{{ $module->description }}</td>
                                    <td class="px-6 py-4 flex gap-2">
                                        <button class="gap-2 px-3 py-2 bg-yellow-400 text-gray-900 rounded-lg flex items-center justify-center" wire:click="edit({{ $module->id }}); document.getElementById('name').focus()">@include('icons.edit') Editar</button>
                                        <button class="gap-2 px-3 py-2 bg-red-500 text-white rounded-lg flex items-center justify-center" onclick="return confirm('¿Estás seguro de que quieres eliminar este elemento?') ? @this.call('destroy', {{ $module->id }}) : false;">@include('icons.delete') Eliminar</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-gray-500 dark:text-gray-400">No hay módulos para mostrar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
