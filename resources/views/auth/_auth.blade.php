@if (Auth::user())
    <span class="p-4 text-blue-100 font-bold bg-amber-600 rounded-lg flex flex-row gap-5 items-center">
        <span class="bg-blue-800 rounded-lg h-full items-center text-center justify-center flex p-1">
            {{ Auth::user()->roles->pluck('name')->join(', ') }}
        </span>
        <span class="font-bold">
            {{ Auth::user()->name }} <br>
            <small>
                {{ Auth::user()->location?Auth::user()->location->name : 'Sin ubicación asignada'}}
            </small>
        </span>
        <a href="{{ route('logout') }}" class="px-3 py-2 bg-red-500 text-white rounded-lg flex flex-row gap-2">@include('icons.logout') Salir</a>
    </span>
@endif
