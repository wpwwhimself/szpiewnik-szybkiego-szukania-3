@extends("layout")

@section("content")

<h1>Witaj w Szpiewniku Szybkiego Szukania</h1>

<p>
    Zadaniem tego śpiewnika jest wyświetlać wszystko, co będzie Ci potrzebne podczas mszy:
    pieśni, części stałe, wszystko w ładnym i zgrabnym porządku.
</p>

<div class="flex-right center wrap">
    <a href="{{ route('sets') }}">
        <x-button>Przeglądaj zestawy</x-button>
    </a>
</div>

@auth

<section>
    <h2>Mój profil</h2>
    <h3>Dane</h3>
    <form action="{{ route('user-update') }}" method="post">
        @csrf
        <div class="flex-right center wrap">
            <x-input type="text" name="name" label="Nazwa użytkownika" value="{{ Auth::user()->name }}" />
            <x-input type="email" name="email" label="Email" value="{{ Auth::user()->email }}" />
            <x-input type="password" name="password" label="Zmień hasło" />
            <x-select name="default_place" label="Domyślne miejsce" value="{{ Auth::user()->default_place }}" :options="$places->pluck('name', 'name')" :empty-option="true" />
        </div>

        <div class="flex-right center">
            <x-button>Popraw dane</x-button>
        </div>
    </form>
    <h3>Uprawnienia</h3>
    <div class="center">
        <strong>Poziom {{ Auth::user()->clearance->id }}: {{ Auth::user()->clearance->name }}</strong><br />
        <div class="flex-right center">
            <span>Możesz:</span>
            <ol style="text-align: left;">
            @foreach (Auth::user()->clearance->allCan as $can)
                <li>{{ $can }}</li>
            @endforeach
            </ol>
        </div>
        <em class="ghost">
            Potrzebujesz wyższych uprawnień?
            <a href="mailto:contact@wpww.pl">Napisz do mnie!</a>
        </em>
    </div>
</section>

@else

<section>
    <h2>Masz konto?</h2>
    <p class="center">Za pomocą konta możesz tworzyć własne zestawy.</p>

    <div class="flex-right center wrap">
        <a href="{{ route('login') }}"><x-button>Zaloguj się</x-button></a>
        <a href="{{ route('register') }}"><x-button>Utwórz konto</x-button></a>
    </div>
</section>

@endauth

@endsection
