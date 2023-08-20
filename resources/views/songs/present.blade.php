@extends("layout")

@section("content")

<div class="container" id="root"></div>
<script src="{{ asset('/js/song.js') }}?{{ time() }}"></script>

<div class="flex-right stretch">
    @auth
    <a href="{{ route('song', ['title_slug' => Str::slug($song->title)]) }}" class="flex-right stretch">
        <x-button>Edytuj pieśń</x-button>
    </a>
    @endauth
</div>

@endsection
