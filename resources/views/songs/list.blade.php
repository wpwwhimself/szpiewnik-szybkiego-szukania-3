@extends("layout")

@section("content")

@if (Auth::user()?->clearance->id >= 2)
<a href="{{ route('song-add') }}" class="flex-right stretch">
    <x-button>Dodaj nową</x-button>
</a>
@endif

<search>
    <x-input type="text" name="search" label="Szukaj po tytule" oninput="searchSongs(event)" />
</search>

<script>
function searchSongs(event) {
    const q = event.target.value;

    // hide songs not matching search
    document.querySelectorAll(`#main-wrapper .list-element`).forEach(el => {
        const hide = (q != "")
            ? !(new RegExp(q, "i").test(el.querySelector(`.main`).textContent))
            : false;
        el.classList.toggle("hidden", hide);
    });

    // cleanup headings (hide if all songs are hidden)
    document.querySelectorAll(`#main-wrapper > h1`).forEach(el => {
        const hide = el.nextElementSibling.querySelectorAll(`.list-element:not(.hidden)`).length == 0;
        el.classList.toggle("hidden", hide);
    });
}
</script>

@foreach ($categories as $cat)
    <h1 class="cap-initial">{{ $cat->name }}</h1>
    <div class="flex-right wrap center">
    @foreach ($songs[$cat->name] as $song)
        <x-list-element class="{{ substr($song->title, 0, 1) != ($initial ?? '') ? 'boldEm' : '' }}"
            :present="route('song-present', ['title_slug' => Str::slug($song->title)])"
            :edit="route('song', ['title_slug' => Str::slug($song->title)])"
            clearance-for-edit="2"
            >
            {{ $song->title }}
        </x-list-element>
        @php $initial = substr($song->title, 0, 1) @endphp
    @endforeach
    </div>
@endforeach

@endsection
