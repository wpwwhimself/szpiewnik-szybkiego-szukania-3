@extends("layout")

@section("content")

<div class="container" id="root"></div>
<script src="{{ asset('/js/set.js') }}"></script>

@auth
<div class="flex-right stretch">
    <a href="{{ route('set', ['set_id' => $set->id]) }}" class="flex-right stretch">
        <x-button>Edytuj mszę</x-button>
    </a>
    <x-button onclick="window.scrollTo({top: 0, behavior: 'smooth'});">
        Na początek
    </x-button>
</div>
@endauth

@endsection
