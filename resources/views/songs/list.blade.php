@extends("layout")

@section("content")

<a href="{{ route('song-add') }}" class="flex-right stretch">
    <x-button>Dodaj nową</x-button>
</a>

@foreach ($categories as $cat)
    <h1 class="cap-initial">{{ $cat->name }}</h1>
    <div class="flex-right wrap center boldEm">
    @foreach ($songs[$cat->name] as $song)
        <a href="{{ route('song', ['title_slug' => Str::slug($song->title)]) }}">
            {{ $song->title }}
        </a>
    @endforeach
    </div>
@endforeach

@endsection