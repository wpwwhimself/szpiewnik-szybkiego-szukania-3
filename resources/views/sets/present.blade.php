@extends("layout")

@section("content")

@auth
<div class="flex-right stretch">
    <a href="{{ route('set', ['set_id' => $set->id]) }}" class="flex-right stretch">
        <x-button>Edytuj mszę</x-button>
    </a>
</div>
@endauth

<div class="container" id="root"></div>
<script src="{{ asset('/js/set.js') }}?{{ time() }}"></script>

<div class="flex-right stretch">
    @auth
    <a href="{{ route('set', ['set_id' => $set->id]) }}" class="flex-right stretch">
        <x-button>Edytuj mszę</x-button>
    </a>
    @endauth
    <x-button onclick="window.scrollTo({top: 0, behavior: 'smooth'});">
        Na początek
    </x-button>
</div>

@endsection
