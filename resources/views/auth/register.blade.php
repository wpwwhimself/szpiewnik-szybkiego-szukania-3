@extends('layout')

@section('content')

<form class="login-form" method="post" action="{{ route("register") }}">
  @csrf
  <h1>Utwórz swoje konto</h1>
  <div class="login-box">
    <x-input type="text" name="name" label="Nazwa użytkownika" :required="true" />
    <x-input type="email" name="email" label="Email kontaktowy" :required="true" />
    <x-input type="password" name="password" label="Hasło" :required="true" />
  </div>
  <x-button type="submit">Zaloguj się</x-button>
</form>

@endsection
