{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Iniciar Sesión - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .sticky-background {
            /* Propiedades para el efecto sticky */
            position: -webkit-sticky;
            /* Soporte para navegadores antiguos */
            position: sticky;
            top: 0;
            z-index: 100;
            /* Asegura que el elemento esté encima de otros */
            background-image: url('/images/bg.webp');
            /* Propiedades para el background hover */
            background-color: #f1f1f1;
            /* Color de fondo inicial */
            transition: background-color 0.3s ease;
            /* Transición suave del color */
            padding: 10px;
            /* Espaciado interno */

        }

        /* Pseudo-clase :hover para cambiar el color de fondo */
        .sticky-background:hover {
            background-color: #1733af;
            /* Nuevo color de fondo al pasar el cursor */
        }
    </style>
</head>

<body class="min-h-screen bg-gradient-to-br from-amber-900 via-amber-800 to-indigo-900 sticky-background">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Logo y header -->
            <div class="text-center">
                <div class="mx-auto h-20 w-20 bg-white rounded-full flex items-center justify-center shadow-lg">
                    <!-- Icono de TV/Cable -->
                    <svg class="h-10 w-10 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                        </path>
                    </svg>
                </div>
                <h2 class="mt-6 text-3xl font-extrabold text-white">
                    Iniciar Sesión
                </h2>
                <p class="mt-2 text-sm text-amber-200">
                    Accede a tu panel de administración
                </p>
            </div>

            <!-- Formulario -->
            <form class="mt-8 space-y-6" action="{{ route('login') }}" method="POST">
                @csrf

                <!-- Card contenedor -->
                <div class="bg-blue-700/10 backdrop-blur-md rounded-xl shadow-2xl p-8 border border-white/20">
                    <div class="space-y-6">
                        <!-- Campo Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-white mb-2">
                                Correo Electrónico
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207">
                                        </path>
                                    </svg>
                                </div>
                                <input id="email" name="email" type="email" autocomplete="email" required
                                    value="{{ old('email') }}" class="block w-full pl-10 pr-3 py-3 border border-gray-300/30 rounded-lg
                                           bg-white/5 backdrop-blur-sm text-white placeholder-gray-300
                                           focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500
                                           transition duration-200" placeholder="correo@tuvision.mx">
                            </div>
                            @error('email')
                            <p class="mt-1 text-sm text-red-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Campo Contraseña -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-white mb-2">
                                Contraseña
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                        </path>
                                    </svg>
                                </div>
                                <input id="password" name="password" type="password" autocomplete="current-password"
                                    required class="block w-full pl-10 pr-3 py-3 border border-gray-300/30 rounded-lg
                                           bg-white/5 backdrop-blur-sm text-white placeholder-gray-300
                                           focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500
                                           transition duration-200" placeholder="••••••••">
                            </div>
                            @error('password')
                            <p class="mt-1 text-sm text-red-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Recordarme y Olvidé contraseña -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input id="remember-me" name="remember" type="checkbox" class="h-4 w-4 text-amber-600 focus:ring-amber-500 border-gray-300 rounded
                                    bg-white/10 backdrop-blur-sm">
                                <label for="remember-me" class="ml-2 block text-sm text-white">
                                    Recordarme
                                </label>
                            </div>
                        </div>

                        <!-- Botón Iniciar Sesión -->
                        <div>
                            <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent
                                    text-sm font-medium rounded-lg text-white bg-gradient-to-r from-amber-600 to-indigo-600
                                    hover:from-amber-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2
                                    transition duration-200 transform hover:scale-105 shadow-lg">
                                <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                    <svg class="h-5 w-5 text-amber-300 group-hover:text-amber-200" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
                                        </path>
                                    </svg>
                                </span>
                                Iniciar Sesión
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Footer -->
            <div class="text-center">
                <p class="text-xs text-amber-200">
                    © {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.
                </p>
            </div>
        </div>
    </div>

    <!-- Efectos visuales de fondo -->
    <div class="fixed inset-0 -z-10 overflow-hidden">
        <div
            class="absolute -top-40 -right-32 w-80 h-80 bg-amber-500 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-pulse">
        </div>
        <div
            class="absolute -bottom-40 -left-32 w-80 h-80 bg-indigo-500 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-pulse">
        </div>
    </div>
</body>

</html>
