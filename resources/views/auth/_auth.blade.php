@if (Auth::user())
    <span class="p-4 text-blue-100 font-bold bg-amber-600 rounded-lg flex flex-row gap-2 items-center">
        <span>
            {{ Auth::user()->name }} - {{ Auth::user()->location?Auth::user()->location->name : 'Sin ubicación asignada'}}
        </span>
        <a href="{{ route('logout') }}" class="px-3 py-2 bg-red-500 text-white rounded-lg flex flex-row gap-2">@include('icons.logout') Salir</a>
    </span>
@endif
