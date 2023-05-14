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
        <a href="{{ route('song', ['title_slug' => Str::slug($song->title)]) }}" class="{{ substr($song->title, 0, 1) != ($initial ?? '') ? 'boldEm' : '' }}">
            {{ $song->title }}
        </a>
        @php $initial = substr($song->title, 0, 1) @endphp
    @endforeach
    </div>
@endforeach

@endsection
