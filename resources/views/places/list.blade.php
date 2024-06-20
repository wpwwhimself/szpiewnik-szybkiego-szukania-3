@extends("layout")

@section("content")

<p>
    Miejsca odzwierciedlają konkretne zwyczaje panujące w kościele,
    jak np. dodatkowe lub pozmieniane elementy mszy.
    Na swoim profilu możesz także wybrać domyślne miejsce,
    jakie będzie się wyświetlać przy otwieraniu zestawów.
</p>

@if (Auth::user()?->clearance->id >= 1)
<a href="{{ route('place-add') }}" class="flex-right stretch">
    <x-button>Dodaj nowe</x-button>
</a>
@endif

<div class="flex-right center wrap">
@foreach ($places as $place)
    <a href="{{ route('place', ['name_slug' => Str::slug($place->name)]) }}">
        {{ $place->name }}
    </a>
@endforeach
</div>

@endsection
