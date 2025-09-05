<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bitacora</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: #2c3e50;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 90vh;
            margin: 0;
        }
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

        .container {
            text-align: center;
        }

        .mov {
            font-size: 5rem;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 5px;
            /* Efecto de gradiente de texto */
            background: linear-gradient(45deg, #0e23e291, #cabe0e, #cf750d);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;

            /* Sombra del texto */
            text-shadow: 0 0 10px rgba(231, 116, 22, 0.925), 0 0 20px rgba(33, 90, 245, 0.3);

            /* Animación del gradiente */
            animation: moveGradient 5s ease-in-out infinite;
            background-size: 200% auto;
        }

        p {
            font-size: 1.5rem;
            letter-spacing: 2px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        /* Definición de la animación */
        @keyframes moveGradient {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }
    </style>
</head>

<body class="sticky-background">
    <div class="flex flex-col">
        <div class="rounded-t-xl bg-blue-900/70 p-10">
            <div >
                <h1>Bienvenido</h1>
                <p>¡Tu vision telecable!</p>
            </div>
        </div>
        @if (Route::has('login'))
        <div class="flex flex-row gap-4 bg-amber-700/70 rounded-b-xl p-5">
            @auth
            <a href="{{ url('/home') }}"
                class="font-semibold text-blue-600 hover:text-blue-900 dark:text-blue-100 dark:hover:text-orange-400 focus:outline focus:rounded-sm focus:outline-orange-500">Home</a>
            @else
            <a href="{{ route('login') }}"
                class="font-semibold text-blue-600 hover:text-blue-900 dark:text-blue-100 dark:hover:text-orange-400 focus:outline focus:rounded-sm focus:outline-orange-500">Login</a>

            @if (Route::has('register'))
            <a href="{{ route('register') }}"
                class="ml-4 font-semibold text-blue-600 hover:text-blue-900 dark:text-blue-100 dark:hover:text-orange-400 focus:outline focus:rounded-sm focus:outline-orange-500">Register</a>
            @endif
            @endauth
        </div>
        @endif
    </div>
</body>

</html>
