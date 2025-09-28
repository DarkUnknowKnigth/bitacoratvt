<div class="min-h-screen dark:bg-slate-900 font-sans p-8 text-gray-900 dark:text-gray-100">
    <!-- Dashboard Header -->
    <div class="flex flex-col md:flex-row gap-2 items-center justify-between mb-8">
        <h1 class="text-3xl font-bold tracking-tight text-blue-800 dark:text-blue-400">Administrador de roles</h1>
        <div class="flex space-x-4">
            <!-- Button to create role -->
            @include('auth._auth')
        </div>
    </div>

    <!-- Main Container -->
    <div class="flex flex-col gap-8">
        <!-- roles and simple progress section -->
        <div class="md:col-span-2 space-y-8">
            <!-- role Progress Summary -->
            <div class="bg-white px-5 dark:bg-slate-800 rounded-xl shadow-lg py-6 flex flex-col items-center justify-center transition-all duration-300 transform hover:scale-[1.01]">
                <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Roles</h2>
                @include('partials._validation')
                <div class="flex flex-col items-center justify-center text-center">
                    <form wire:submit="{{$role_id?'update('.$role_id.')':'save()'}}" method="post">
                        <div class="md:grid md:grid-cols-2 flex flex-col gap-5 items-center justify-center w-full">
                            <label for="name">
                                Nombre:
                            </label>
                            <input type="text" name="name" wire:model="name" id="name" class="w-full md:w-auto rounded-lg px-3 py2 text-blue-950">
                            <label for="slug">
                                Slug:
                            </label>
                            <input type="text" name="slug" wire:model="slug" id="slug" class="w-full md:w-auto rounded-lg px-3 py2 text-blue-950">
                            <button type="submit" class="col-span-2 w-full text-white md:w-auto px-3 py-2 rounded-lg bg-amber-600 flex flex-row gap-2 items-center justify-center">@include('icons.save') Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- role List -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Listado de roles</h2>
                <ul class="space-y-4">
                    @forelse ($roles as $role)
                        <li class="flex flex-col gap-4 p-4 bg-gray-50 dark:bg-slate-700 rounded-lg shadow-sm transition-transform transform hover:scale-[1.01] hover:shadow-md">
                            <div class="flex flex-col md:flex-row items-center justify-between w-full gap-4">
                                <span class="md:flex-wrap w-full">
                                    {{ $role->name }} - {{$role->slug}}
                                </span>
                                <div class="flex flex-col md:flex-row gap-2 w-full md:w-auto">
                                    <button class="bg-blue-500 text-white rounded-lg flex flex-row gap-2 md:w-auto w-full items-center justify-center px-3 py-2" wire:click="toggleModuleAssignment({{ $role->id }})">@include('icons.add') Módulos</button>
                                    <button class="bg-yellow-400 text-gray-900 rounded-lg flex flex-row gap-2 md:w-auto w-full items-center justify-center px-3 py-2"  wire:click="edit({{ $role->id }}); document.getElementById('name').focus()">@include('icons.edit') Editar</button>
                                    <button class="bg-red-500 text-white rounded-lg flex flex-row gap-2 md:w-auto w-full items-center justify-center px-3 py-2" onclick="return confirm('¿Estás seguro de que quieres eliminar este elemento?') ? @this.call('destroy', {{ $role->id }}) : false;">@include('icons.delete') Eliminar</button>
                                </div>
                            </div>
                            @if ($editingModulesForRoleId === $role->id)
                                <div class="mt-4 border-t pt-4">
                                    <h3 class="font-semibold text-lg mb-2">Asignar Módulos a: {{ $role->name }}</h3>
                                    <form wire:submit.prevent="assignModules({{ $role->id }})">
                                        <select wire:model="selectedModules" multiple class="w-full rounded-lg px-3 py-2 text-blue-950 mb-4" size="8">
                                            @foreach($allModules as $module)
                                                <option value="{{ $module->id }}">{{ $module->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="flex flex-col md:flex-row gap-2">
                                            <button type="submit" class="w-full text-white md:w-auto px-3 py-2 rounded-lg bg-green-600 flex flex-row gap-2 items-center justify-center">@include('icons.save') Guardar Módulos</button>
                                            <button type="button" wire:click="resetFields" class="w-full text-white md:w-auto px-3 py-2 rounded-lg bg-gray-500 flex flex-row gap-2 items-center justify-center">Cancelar</button>
                                        </div>
                                    </form>
                                </div>
                            @endif
                        </li>
                        <li>
                            @if ($role->modules->isNotEmpty())
                                <div class="flex flex-col gap-2 mt-2 text-sm text-gray-600 dark:text-gray-300">
                                    <strong>Módulos asignados:</strong>
                                    <div class="md:flex md:flex-row grid grid-cols-3 gap-2 items-center justify-left ml-4 w-full">
                                        @foreach ($role->modules as $module)
                                            <span class="font-semibold bg-amber-600 rounded-lg px-2 py-1 text-white">{{ $module->name }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                        </li>
                    @empty
                        <li class="text-center text-gray-500 dark:text-gray-400">No hay roles para mostrar.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
