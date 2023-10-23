@props(["title" => null])

<header>
    <x-logo />
    <div class="titles">
        <h1 id="page-title">{{ $title ?: env("APP_NAME") }}</h1>
        @if ($title)
        <h2>{{ env("APP_NAME") }}</h2>
        @endif
    </div>
    <nav class="flex-right">
        <a href="{{ route('sets') }}">Zestawy</a>
    @auth
        <a href="{{ route('songs') }}">Pieśni</a>
        <a href="{{ route('places') }}">Miejsca</a>
        <a href="{{ route('ordinarium') }}">Części stałe</a>
        <a href="{{ route('formulas') }}">Formuły</a>
    @endauth
    </nav>
</header>
