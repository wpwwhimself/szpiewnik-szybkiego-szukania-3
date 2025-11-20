@extends("layouts.shipyard.admin")
@section("title", "Pieśni i utwory")

@section("content")

@if (Auth::user()?->hasRole("song-manager"))
<x-shipyard.ui.button
    :action="route('song-add')"
    label="Dodaj nową"
    icon="plus"
/>
@endif

<x-shipyard.app.section
    title="Filtry"
    icon="filter"
    :extended="true"
>
    <x-shipyard.ui.input type="text"
        name="search"
        label="Szukaj po tytule"
        icon="file-search"
        oninput="searchSongs()"
    />

    <div class="flex right middle wrap">
        <span role="label-wrapper" class="ghost">
            <x-shipyard.app.icon name="select-place" />
            <label>Szukaj po przeznaczeniu</label>
        </span>
        @foreach ([
            ["Wejście", 0],
            ["Dary", 2],
            ["Komunia", 4],
            ["Uwielbienie", 6],
            ["Zakończenie", 8],
            ["Pozostałe", "*"],
        ] as [$label, $pref_index])
        <span role="filter" class="button sleek" onclick="showSongsByPref('{{ $pref_index }}', this);">{{ $label }}</span>
        @endforeach
        <span role="filter-reset" class="button hidden" onclick="filterReset()">↺</span>
    </div>
</x-shipyard.app.section>

<script>
//* helpers *//
function cleanUpHeadings() {
    // cleanup headings (hide if all songs are hidden)
    document.querySelectorAll(`[role="song-list"] > h1`).forEach(el => {
        const hide = el.nextElementSibling.querySelectorAll(`.list-element:not(.hidden)`).length == 0;
        el.classList.toggle("hidden", hide);
    });
}

function activateFilterBtn(btn = undefined) {
    document.querySelectorAll(`[role='filter'].accent-border`)?.forEach(el => el.classList.remove("accent-border"));
    btn?.classList.add("accent-border");
    showFilterResetBtn();
}

function showFilterResetBtn(visible = true) {
    document.querySelector(`[role='filter-reset']`)?.classList.toggle("hidden", !visible);
}

//* search functions *//
function searchSongs() {
    filterReset();

    const q = document.querySelector("input[name='search']").value;

    // hide songs not matching search
    document.querySelectorAll(`[role="song-list"] .list-element`).forEach(el => {
        const title = el.querySelector(`.main`).textContent;
        const hide = (q != "")
            ? !(new RegExp(q, "i").test(title.replace(/[\.,\-]/g, "")))
            : false;
        el.classList.toggle("hidden", hide);
    });

    cleanUpHeadings();
}

function showSongsByPref(pref_index, btn) {
    searchReset();

    document.querySelectorAll(`[role="song-list"] .list-element`).forEach(el => {
        const prefs = el.querySelector(`.main`).dataset.prefs;
        const hide = (pref_index != "*")
            ? prefs.charAt(pref_index) != "1"
            : prefs.substring(0, 9) != "0/0/0/0/0";
        el.classList.toggle("hidden", hide);
    });

    cleanUpHeadings();
    activateFilterBtn(btn);
}

function searchReset() {
    document.querySelector("input[name='search']").value = "";
}

function filterReset() {
    activateFilterBtn();
    showFilterResetBtn(false);

    document.querySelectorAll(`[role="song-list"] .list-element`).forEach(el => el.classList.remove("hidden"));
    cleanUpHeadings();
}
</script>

<div class="flex down middle" role="song-list">
@foreach ($categories as $cat)
    <h1>{{ Str::of($cat->name)->ucfirst() }}</h1>
    <div class="flex right wrap center">
    @foreach ($songs[$cat->name] as $song)
        <x-list-element class="{{ substr($song->title, 0, 1) != ($initial ?? '') ? 'boldEm' : '' }}"
            :present="route('song-present', ['title_slug' => Str::slug($song->title)])"
            :edit="route('song', ['title_slug' => Str::slug($song->title)])"
            role-for-edit="2"
            :data-prefs="$song->preferences"
        >
            {{ $song->title }}
        </x-list-element>
        @php $initial = substr($song->title, 0, 1) @endphp
    @endforeach
    </div>
@endforeach
</div>

@endsection
