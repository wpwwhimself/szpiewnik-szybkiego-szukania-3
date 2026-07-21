@extends("shipyard::layouts.admin")
@section("title", "Zestawy")

@section("content")

@auth
<x-shipyard::ui.button
    icon="plus"
    label="Dodaj nowy"
    action="none"
    onclick="openModal('add-set');"
    class="primary"
/>
@endauth

@guest
<p>
    Obecnie jesteś niezalogowany
    – poniżej widoczne są tylko publicznie dostępne zestawy.
</p>
@endguest

@foreach ($setGroups as $is_public => $sets)
<x-shipyard::app.h lvl="3" :icon="model_icon('sets')">
    @switch ($is_public)
        @case (0) Moje zestawy @break
        @case (1) Publiczne zestawy @break
        @case (2) Zestawy innych @break
    @endswitch
</x-shipyard::app.h>

<table>
    <thead>
        <tr>
            <th class="sortable">Nazwa</th>
            <th class="sortable">Formuła</th>
            @if ($is_public === 2) <th class="sortable">Twórca</th> @endif
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($sets as $set)
        @continue (!($set->public || $set->user_id == Auth::id() || Auth::user()?->hasRole("technical")))
        <tr>
            <td>
                <span style="color: {{ $set->colorData->display_color }};">⬤</span>
                {{ $set->name }}
            </td>
            <td>{{ $set->formulaData->name }}</td>
            @if ($is_public === 2)
            <td>{{ $set->user }}</td>
            @endif
            <td>
                <a href="{{ route('set-present', ['set_id' => $set->id]).(Auth::user()?->default_place ? '?place='.Str::slug(Auth::user()->default_place) : '') }}">Otwórz</a>
                @if ($set->user_id === Auth::id()) <a href="{{ route('set', ['set_id' => $set->id]) }}">Edytuj</a> @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<x-set-color-counter :sets="$sets" />

@endforeach

<x-shipyard::app.h lvl="3" icon="more">Dodatkowe źródła</x-shipyard::app.h>
<ul>
    <li><a href="https://musicamsacram.pl/propozycje-spiewow">Musicam Sacram</a></li>
</ul>

@endsection
