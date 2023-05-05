@extends("layout")

@section("content")

@auth
<a href="{{ route('set-add') }}" class="flex-right stretch">
    <x-button>Dodaj nowy</x-button>
</a>
@endauth

<h1>Załaduj mszę</h1>
<div>
@foreach ($formulas as $formula) @if(count($formula->sets))
    <h2>{{ $formula->name }}</h2>
    <div class="flex-right center wrap">
    @foreach ($sets[$formula->name] as $set)
        <a href="{{ route('set-present', ['set_id' => $set->id]) }}">{{ $set->name }}</a>
    @endforeach
    </div>
@endif @endforeach
</div>

@endsection
