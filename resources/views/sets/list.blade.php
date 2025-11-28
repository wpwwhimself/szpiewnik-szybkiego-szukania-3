@extends("layouts.shipyard.admin")
@section("title", "Zestawy")

@section("content")

@auth
<x-shipyard.ui.button
    :action="route('set-add')"
    label="Dodaj nowy"
    icon="plus"
/>
@endauth

@guest
<p>
    Obecnie jesteś niezalogowany
    – poniżej widoczne są tylko zestawy,
    które zostały ustawione przez innych użytkowników jako publiczne.
</p>
@endguest

<div role="set-list">
@foreach ($formulas as $formula) @if(count($formula->sets))
    <h2>{{ $formula->name }}</h2>
    <div class="flex right center wrap">
    @foreach ($sets[$formula->name] as $set)
        @continue (!($set->public || $set->user_id == Auth::id() || Auth::user()->hasRole("technical")))

        <div class="flex down center no-gap">
            @if ($set->user->id != Auth::id()) <label class="ghost">{{ $set->user->name }}</label> @endif
            <x-list-element
                :present="route('set-present', ['set_id' => $set->id]).(Auth::user()?->default_place ? '?place='.Str::slug(Auth::user()->default_place) : '')"
                :edit="route('set', ['set_id' => $set->id])"
                role-for-edit="1"
                >
                <span style="color: {{ $set->colorData->display_color }};">⬤</span>
                {{ $set->name }}
            </x-list-element>
        </div>
    @endforeach
    </div>
    <x-set-color-counter :sets="$sets[$formula->name]" />
@endif @endforeach
</div>

@endsection
