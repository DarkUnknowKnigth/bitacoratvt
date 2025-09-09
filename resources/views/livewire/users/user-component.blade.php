<div class="min-h-screen dark:bg-slate-900 font-sans p-8 text-gray-900 dark:text-gray-100">
    <!-- Dashboard Header -->
    <div class="flex flex-col md:flex-row gap-2 items-center justify-between mb-8">
        <h1 class="text-3xl font-bold tracking-tight text-blue-800 dark:text-blue-400">Administrador de usuarios</h1>
        <div class="flex space-x-4">
            <!-- Button to create user -->
            @if (Auth::user())
                <span class="p-4 text-blue-100 font-bold bg-amber-600 rounded-lg ">
                    {{ Auth::user()->name }} - {{ Auth::user()->location?Auth::user()->location->name : 'Sin ubicación asignada'}}
                </span>
            @endif
        </div>
    </div>

    <!-- Main Container -->
    <div class="flex flex-col gap-8">
        <!-- users and simple progress section -->
        <div class="md:col-span-2 space-y-8">
            <!-- user Progress Summary -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg py-6 flex flex-col items-center justify-center transition-all duration-300 transform hover:scale-[1.01]">
                <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Usuarios</h2>
                <div class="flex flex-col items-center justify-center text-center">
                    <form action="" method="post" x-data="{email:'', name:'', location:0}">
                        <div class="grid md:grid-cols-4 grid-cols-1 md:flex-row gap-5 items-center justify-center w-full">
                            <label for="name">
                                Nombre:
                            </label>
                            <input type="text" name="name" wire:model="name" id="name" class="w-full md:w-auto rounded-lg px-3 py2 text-blue-950">

                            <label for="email">
                                Email:
                            </label>
                            <input type="text" name="email" wire:model="email" id="email" class="w-full md:w-auto rounded-lg px-3 py2 text-blue-950">
                            <label for="password">
                                Email:
                            </label>
                            <input type="text" name="password" wire:model="password" id="password" class="w-full md:w-auto rounded-lg px-3 py2 text-blue-950">
                            <label for="location">
                                Lugar de trabajo:
                            </label>
                            <select name="location" wire:model="location" id="location"  class="w-full md:w-auto rounded-lg px-3 py2 text-blue-950">
                                <option value="">Seleccione</option>
                                @foreach ($locations as $l)
                                    <option value="{{ $l->id }}"> {{$l->name}}: {{$l->address}}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="col-span-2 w-full text-white md:w-auto px-3 py-2 rounded-lg bg-amber-600 flex flex-row gap-2">@include('icons.save') Guardar</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- user List -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Listado de usuarios</h2>
                <ul class="space-y-4">
                    @forelse ($users as $user)
                        <li class="flex flex-col gap-4 md:flex-row items-center justify-between p-4 bg-gray-50 dark:bg-slate-700 rounded-lg shadow-sm transition-transform transform hover:scale-[1.01] hover:shadow-md">
                            <span class="md:w-3/4 w-full">{{ $user->name }} - {{$user->email}} <br> {{$user->location? $user->location->name: 'Sin asignacion'}}</span>
                            <button class="px-3 py-2 bg-yellow-400 text-gray-900 rounded-lg flex flex-row gap-2">@include('icons.edit') Editar</button>
                            <button class="px-3 py-2 bg-red-500 text-white rounded-lg flex flex-row gap-2">@include('icons.delete') Eliminar</button>
                        </li>
                    @empty
                        <li class="text-center text-gray-500 dark:text-gray-400">No hay usuarios para mostrar.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>

