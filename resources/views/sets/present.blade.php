@extends("layout")

@section("content")

<div class="container" id="root"></div>
<script src="{{ asset('/js/react/set.js') }}?{{ time() }}"></script>
<script src="{{ asset("js/note-transpose.js") }}"></script>

<div class="flex-right stretch">
    @auth
    <a href="{{ route('set', ['set_id' => $set->id]) }}" class="flex-right stretch">
        <x-button>Edytuj mszę</x-button>
    </a>

    @if ($set->user->id != Auth::id() && Auth::user()->clearance->id >= 1)
    <a href="{{ route('set-copy-for-user', ['set' => $set]) }}" class="flex-right stretch">
        <x-button>Utwórz i edytuj kopię pod siebie</x-button>
    </a>
    @endif

    @endauth
    <x-button onclick="window.scrollTo({top: 0, behavior: 'smooth'});">
        Na początek
    </x-button>
</div>

@endsection
