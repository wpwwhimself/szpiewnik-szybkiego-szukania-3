<footer>
    <div class="auth flex-down">
        @auth
        <span>Zalogowany jako <strong>{{ Auth::user()->name }}</strong></span>
        <a href="{{ route("logout") }}">Wyloguj</a>
        @else
        <a href="{{ route("login") }}">Zaloguj się</a>
        @endauth
    </div>
    <div class="titles">
        <h2>Szpiewnik Szybkiego Szukania 3</h2>
        <p>Projekt i wykonanie: <a href="http://wpww.pl/">Wojciech Przybyła</a></p>
        <p>&copy; 2023 - {{ \Carbon\Carbon::today()->format("Y") }}</p>
    </div>
    <x-logo />
</footer>
