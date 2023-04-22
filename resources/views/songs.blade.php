@extends("layout")

@section("content")

@foreach ($categories as $cat)
    <h1 class="cap-initial">{{ $cat->name }}</h1>
    <div class="flex-right wrap center boldEm">
    @foreach ($songs[$cat->name] as $song)
        <a href="{{ route('song', ['title' => Str::slug($song->title)]) }}">
            {{ $song->title }}
        </a>
    @endforeach
    </div>
@endforeach

@endsection
