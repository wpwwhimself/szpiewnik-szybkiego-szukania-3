@extends("layout")

@section("content")

@if (Auth::user()?->clearance->id >= 2)
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
