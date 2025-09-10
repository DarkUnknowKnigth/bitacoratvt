<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <title>Bitácora</title>
        @livewireStyles
    </head>
    <body>
        @if (session('status'))
            <div class="bg-amber-600 border text-black px-4 py-3 font-bold text-center relative" role="alert">
                <span class="block sm:inline">{{ session('status') }}</span>
            </div>
        @endif
        <div class="min-h-screen bg-slate-100 dark:bg-slate-900 font-sans text-gray-900 dark:text-gray-100">
            <header class="m-0 relative z-10 p-4 rounded-b-xl bg-blue-950">
                <div class="flex items-center justify-between">
                    <a href="{{ route('home') }}" class="py-2 text-3xl font-bold tracking-tight text-blue-700 dark:text-blue-400">Bitacora TVT</a>
                </div>
            </header>
            <nav class="p-8 -mt-4 bg-blue-900 rounded-b-xl">
                <ul class="flex flex-col gap-5 md:flex-row font-bold">
                    <li class="text-amber-500 dark:text-white hover:text-blue-800 hover:bg-amber-600 rounded-lg"><a class="px-2 py-2" href="{{ route('home') }}">Dashboard</a></li>
                    <li class="text-amber-500 dark:text-white hover:text-blue-800 hover:bg-amber-600 rounded-lg"><a class="px-2 py-2" href="{{ route('tasks') }}">Tareas</a></li>
                    <li class="text-amber-500 dark:text-white hover:text-blue-800 hover:bg-amber-600 rounded-lg"><a class="px-2 py-2" href="{{ route('validations') }}">Validaciones</a></li>
                    <li class="text-amber-500 dark:text-white hover:text-blue-800 hover:bg-amber-600 rounded-lg"><a class="px-2 py-2" href="{{ route('users') }}">Usuarios</a></li>
                    <li class="text-amber-500 dark:text-white hover:text-blue-800 hover:bg-amber-600 rounded-lg"><a class="px-2 py-2" href="{{ route('reviews') }}">Bitacoras</a></li>
                    <li class="text-amber-500 dark:text-white hover:text-blue-800 hover:bg-amber-600 rounded-lg"><a class="px-2 py-2" href="{{ route('locations') }}">Sucursales</a></li>
                </ul>
            </nav>
            {{ $slot }}
        </div>
    </body>
    @livewireScripts
</html>
