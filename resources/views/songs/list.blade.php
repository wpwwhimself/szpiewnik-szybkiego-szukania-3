@extends("layout")

@section("content")

@if (Auth::user()?->clearance->id >= 2)
<a href="{{ route('song-add') }}" class="flex-right stretch">
    <x-button>Dodaj nową</x-button>
</a>
@endif

@foreach ($categories as $cat)
    <h1 class="cap-initial">{{ $cat->name }}</h1>
    <div class="flex-right wrap center">
    @foreach ($songs[$cat->name] as $song)
        <x-list-element class="{{ substr($song->title, 0, 1) != ($initial ?? '') ? 'boldEm' : '' }}"
            :present="route('song-present', ['title_slug' => Str::slug($song->title)])"
            :edit="route('song', ['title_slug' => Str::slug($song->title)])"
            >
            {{ $song->title }}
        </x-list-element>
        @php $initial = substr($song->title, 0, 1) @endphp
    @endforeach
    </div>
@endforeach

@endsection
