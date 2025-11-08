@extends("layouts.shipyard.admin")
@section("title", "Miejsca")

@section("content")

<p>
    Miejsca odzwierciedlają konkretne zwyczaje panujące w kościele,
    jak np. dodatkowe lub pozmieniane elementy mszy.
    Na swoim profilu możesz także wybrać domyślne miejsce,
    jakie będzie się wyświetlać przy otwieraniu zestawów.
</p>

@if (Auth::user()?->hasRole("place-manager"))
<x-shipyard.ui.button
    :action="route('place-add')"
    label="Dodaj nowe"
    icon="plus"
/>
@endif

<div class="flex right center wrap">
@foreach ($places as $place)
    <a href="{{ route('place', ['name_slug' => Str::slug($place->name)]) }}">
        {{ $place->name }}
    </a>
@endforeach
</div>

@endsection
