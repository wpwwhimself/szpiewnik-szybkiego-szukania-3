@extends("layout")

@section("content")

<h1>Witaj w Szpiewniku Szybkiego Szukania</h1>

<a href="{{ route('sets') }}">
    <x-button>Przeglądaj zestawy</x-button>
</a>

@auth

<h2>Mój profil</h2>
<form action="{{ route('user-update') }}" method="post">
    @csrf
    <div class="flex-right center wrap">
        <x-input type="text" name="name" label="Nazwa użytkownika" value="{{ Auth::user()->name }}" />
        <x-input type="email" name="email" label="Email" value="{{ Auth::user()->email }}" />
        <x-input type="password" name="password" label="Zmień hasło" />
    </div>

    <div class="flex-right center">
        <x-button>Popraw dane</x-button>
    </div>
</form>

@endauth

@endsection
