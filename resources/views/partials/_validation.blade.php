@if ($errors->any())
    <div class="border my-2 border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
