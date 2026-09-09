@extends("shipyard::layouts.admin")
@section("title", "Części stałe")

@section("content")

<x-shipyard::app.card>
    Części stałe są pogrupowane w zestawy oznaczone kolorami, które luźno odpowiadają ich przeznaczeniu.
    Wybierz zestaw, aby wyświetlić jego elementy.
    
    <div class="flex right center middle">
        <x-shipyard::ui.button
            icon="magnify"
            label="Wyszukiwarka"
            :action="route('ordinarium-search')"
        />
    </div>
</x-shipyard::app.card>

@foreach ([
    0 => "Zwykłe",
    1 => "Melancholijne",
    2 => "Świąteczne",
] as $color_group => $label)
    <x-shipyard::app.section
        :title="$label"
        inner-class="grid but-mobile-down"
        inner-style="--col-count: 3;"
    >
        @foreach ($colors->filter(fn ($clr) => $clr->group === $color_group) as $color)
        <div class="ordTile">
            <div class="ordTitleBox" style="border-color: {{ $color->display_color }}">
                <a href="{{ route('ordinarius-present', ['color' => $color->name]) }}">
                    <h2>{{ $color->display_name ?? $color->name }}</h2>
                </a>
                <p>{{ $color->desc }}</p>
            </div>
            <div class="flex right wrap center">
                @if (Auth::user()?->hasRole("ordinarius-manager"))
                @foreach ($ordinarium[$color->name] as $ordinarius)
                <a href="{{ route('ordinarius', ['color_code' => $color->name, 'part' => $ordinarius->part]) }}">
                    {{ $ordinarius->part }}
                    @if(count($ordinarius->sheet_music_variants) > 1)
                    <small class="ghost">({{ count($ordinarius->sheet_music_variants) }} war.)</small>
                    @endif
                </a>
                @endforeach
                @endif
            </div>
        </div>
        @endforeach
    </x-shipyard::app.section>
@endforeach

@if (Auth::user()?->hasRole("ordinarius-manager"))
<x-shipyard::app.section
    title="Pozostałe"
    inner-class="grid but-mobile-down"
    inner-style="--col-count: 2;"
>
    <div class="ordTile">
        <div class="ordTitleBox">
            <h1>Uniwersalne</h1>
            <p>dużo ich nie ma, ale...</p>
        </div>
        <div class="flex right wrap center">
            @foreach ($ordinarium["*"] as $ordinarius)
            <a href="{{ route('ordinarius', ['color_code' => "*", 'part' => $ordinarius->part]) }}">
                {{ $ordinarius->part }}
            </a>
            @endforeach
        </div>
    </div>
    <div class="ordTile">
        <div class="ordTitleBox">
            <h1>Okazjonalne</h1>
            <p>na potrzeby świąt</p>
        </div>
        <div class="flex right wrap center">
            @foreach ($ordinarium["events"] as $ordinarius)
            <a href="{{ route('ordinarius', ['color_code' => Str::slug($ordinarius->color_code), 'part' => $ordinarius->part]) }}">
                {{ $ordinarius->part }} ({{ $ordinarius->color_code }})
            </a>
            @endforeach
        </div>
    </div>
</x-shipyard::app.section>
@endif

@endsection
