@extends("layout")

@section("content")

<div class="grid-3">
    @foreach ($colors as $color)
    <div class="ordTile">
        <div class="ordTitleBox" style="border-color: {{ $color->display_color ?? $color->name }}">
            <a href="{{ route('ordinarius-present', ['color' => $color->name]) }}">
                <h1>{{ $color->display_name ?? $color->name }}</h1>
            </a>
            <p>{{ $color->desc }}</p>
        </div>
        <div class="flex-right wrap center">
            @foreach ($ordinarium[$color->name] as $ordinarius)
            <a href="{{ route('ordinarius', ['color_code' => $color->name, 'part' => $ordinarius->part]) }}">
                {{ $ordinarius->part }}
            </a>
            @endforeach
        </div>
    </div>
    @endforeach
{{-- </div>

<div class="grid-2"> --}}
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
</div>

@endsection
