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
    – poniżej widoczne są tylko publicznie dostępne zestawy.
</p>
@endguest

@foreach ($setGroups as $is_public => $sets)
<x-shipyard.app.h lvl="3" :icon="model_icon('sets')">
    @if ($is_public)
    Publiczne zestawy
    @else
    Moje zestawy
    @endif
</x-shipyard.app.h>

<table>
    <thead>
        <tr>
            <th class="sortable">Nazwa</th>
            <th class="sortable">Formuła</th>
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
            <td>
                <a href="{{ route('set-present', ['set_id' => $set->id]).(Auth::user()?->default_place ? '?place='.Str::slug(Auth::user()->default_place) : '') }}">
                    Otwórz
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<x-set-color-counter :sets="$sets" />

@endforeach

<x-shipyard.app.h lvl="3" icon="more">Dodatkowe źródła</x-shipyard.app.h>
<ul>
    <li><a href="https://musicamsacram.pl/propozycje-spiewow">Musicam Sacram</a></li>
</ul>

@endsection
