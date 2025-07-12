@extends("layout")

@section("content")

<p>
    Części stałe są pogrupowane w zestawy oznaczone kolorami, które luźno odpowiadają ich przeznaczeniu.
    Wybierz zestaw, aby wyświetlić jego elementy.
</p>

<div class="grid-3">
    @foreach ($colors as $color)
    <div class="ordTile">
        <div class="ordTitleBox" style="border-color: {{ $color->display_color }}">
            <a href="{{ route('ordinarius-present', ['color' => $color->name]) }}">
                <h1>{{ $color->display_name ?? $color->name }}</h1>
            </a>
            <p>{{ $color->desc }}</p>
        </div>
        <div class="flex-right wrap center">
            @if (Auth::user()?->clearance->id > 1)
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

    @if (Auth::user()?->clearance->id > 1)
    <div class="ordTile">
        <div class="ordTitleBox">
            <h1>Uniwersalne</h1>
            <p>dużo ich nie ma, ale...</p>
        </div>
        <div class="flex-right wrap center">
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
        <div class="flex-right wrap center">
            @foreach ($ordinarium["events"] as $ordinarius)
            <a href="{{ route('ordinarius', ['color_code' => Str::slug($ordinarius->color_code), 'part' => $ordinarius->part]) }}">
                {{ $ordinarius->part }} ({{ $ordinarius->color_code }})
            </a>
            @endforeach
        </div>
    </div>
    @endif
</div>

@endsection
