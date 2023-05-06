@extends('layout')

@section('content')

<form class="login-form" method="post" action="{{ route("authenticate") }}">
  @csrf
  <h1>Zaloguj się</h1>
  <div class="flex-right center wrap">
    <x-input type="text" name="name" label="Nazwa użytkownika" :required="true" />
    <x-input type="password" name="password" label="Hasło" :required="true" />
  </div>
  <div class="flex-right stretch">
    <x-button type="submit">Zaloguj się</x-button>
  </div>
</form>

<div class="flex-down center">
    <h2>Nie masz jeszcze konta?</h2>
    <a href="{{ route('register') }}">
        <x-button>Zarejestruj się</x-button>
    </a>
</div>

@endsection
