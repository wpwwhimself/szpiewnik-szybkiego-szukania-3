@extends('layout')

@section('content')

<form class="login-form" method="post" action="{{ route("register-process") }}">
  @csrf
  <h1>Utwórz swoje konto</h1>
  <p>
    Zakładając konto, będziesz mieć możliwość tworzenia własnych zestawów (nie tylko przeglądania tych publicznych),
    a także kilka innych korzyści, których obecnie nie mogę wypisać.
    Ale aplikacja jest nadal w fazie rozwojowej, więc kto wie, co nowego się tu znajdzie.
  </p>
  <div class="flex-right center wrap">
    <x-input type="text" name="name" label="Nazwa użytkownika" :required="true" />
    <x-input type="email" name="email" label="Email kontaktowy" :required="true" />
    <x-input type="password" name="password" label="Hasło" :required="true" />
    <x-input type="password" name="password_confirm" label="Potwierdź hasło" :required="true" />
    <x-input type="number" name="spamtest" label="Test antyspamowy: cztery razy sześć?" :required="true" />
  </div>
  <div class="flex-right stretch">
    <x-button type="submit">Załóż konto</x-button>
  </div>
</form>

@endsection
