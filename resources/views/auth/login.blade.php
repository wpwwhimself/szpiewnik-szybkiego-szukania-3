@extends('layout')

@section('content')

<form class="login-form" method="post" action="{{ route("authenticate") }}">
  @csrf
  <h1>Zaloguj się</h1>
  <div class="login-box">
    <x-input type="password" name="password" label="Hasło" :required="true" />
  </div>
  <div class="flex-right stretch">
    <x-button type="submit">Zaloguj się</x-button>
  </div>
</form>

@endsection
