@props(["title" => null])

<header>
    <x-logo />
    <div class="titles">
        <h1 id="page-title">{{ $title ?: env("APP_NAME") }}</h1>
        @if ($title)
        <h2>{{ env("APP_NAME") }}</h2>
        @endif
    </div>
    <nav class="flex-down but-mobile-right">
    @auth
        <a href="{{ route('songs') }}">Pieśni</a>
        <a href="{{ route('places') }}">Miejsca</a>
        @if (Auth::user()->clearance->id >= 2) <a href="{{ route('ordinarium') }}">Części stałe</a> @endif
        @if (Auth::user()->clearance->id >= 3) <a href="{{ route('formulas') }}">Formuły</a> @endif
    @endauth
    </nav>
</header>
