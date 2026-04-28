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

<table>
    <thead>
        <tr>
            <th class="sortable">Nazwa</th>
            <th class="sortable">Formuła</th>
            <th class="sortable">Twórca</th>
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
            <td @class([
                "ghost" => $set->user->id != Auth::id(),
            ])>
                @if ($set->user->id != Auth::id())
                {{ $set->user->name }}
                @endif
            </td>
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

<x-shipyard.app.h lvl="3" icon="more">Dodatkowe źródła</x-shipyard.app.h>
<ul>
    <li><a href="https://musicamsacram.pl/propozycje-spiewow">Musicam Sacram</a></li>
</ul>

@endsection
