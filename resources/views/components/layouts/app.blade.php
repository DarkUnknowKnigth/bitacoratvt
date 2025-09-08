<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <title>{{ $title ?? 'Page Title' }}</title>
        @livewireStyles
    </head>
    <body>
        <div class="min-h-screen bg-slate-100 dark:bg-slate-900 font-sans text-gray-900 dark:text-gray-100">
            <header class="md:mb-8 mb-2 p-4">
                <div class="flex items-center justify-between">
                    <a href="{{ route('home') }}" class="text-3xl font-bold tracking-tight text-blue-800 dark:text-blue-400">Bitacora TVT</a>
                </div>
            </header>
            <nav class="p-8">
                <ul class="flex flex-col gap-5 md:flex-row font-bold">
                    <li class="text-amber-500 dark:text-white p-2 hover:text-blue-800 hover:bg-amber-600 rounded-lg"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="text-amber-500 dark:text-white p-2 hover:text-blue-800 hover:bg-amber-600 rounded-lg"><a href="{{ route('tasks') }}">Tareas</a></li>
                    <li class="text-amber-500 dark:text-white p-2 hover:text-blue-800 hover:bg-amber-600 rounded-lg"><a href="{{ route('validations') }}">Validaciones</a></li>
                    <li class="text-amber-500 dark:text-white p-2 hover:text-blue-800 hover:bg-amber-600 rounded-lg"><a href="{{ route('users') }}">Usuarios</a></li>
                    <li class="text-amber-500 dark:text-white p-2 hover:text-blue-800 hover:bg-amber-600 rounded-lg"><a href="{{ route('reviews') }}">Bitacora</a></li>
                </ul>
            </nav>
            {{ $slot }}
        </div>
    </body>
    @livewireScripts
</html>
