@extends("layout")

@section("content")

@auth
<a href="{{ route('set-add') }}" class="flex-right stretch">
    <x-button>Dodaj nowy</x-button>
</a>
@endauth

<h1>Załaduj mszę</h1>

@guest
<p>
    Obecnie jesteś niezalogowany
    – poniżej widoczne są tylko zestawy,
    które zostały ustawione przez innych użytkowników jako publiczne.
</p>
@endguest

<div>
@foreach ($formulas as $formula) @if(count($formula->sets))
    <h2>{{ $formula->name }}</h2>
    <div class="flex-right center wrap">
    @foreach ($sets[$formula->name] as $set)
        @continue (!($set->public || $set->user_id == Auth::id() || Auth::user()->clearance_id >= 4))

        <div class="flex-down center tight">
            @if ($set->user->id != Auth::id()) <label>{{ $set->user->name  }}</label> @endif
            <x-list-element
                :present="route('set-present', ['set_id' => $set->id]).(Auth::user()?->default_place ? '?place='.Str::slug(Auth::user()->default_place) : '')"
                :edit="route('set', ['set_id' => $set->id])"
                clearance-for-edit="1"
                >
                {{ $set->name }}
            </x-list-element>
        </div>
    @endforeach
    </div>
@endif @endforeach
</div>

@endsection
