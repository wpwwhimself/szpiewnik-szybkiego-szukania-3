@extends("layouts.shipyard.admin")

@section("content")

<h1>Witaj w Szpiewniku Szybkiego Szukania</h1>

<p>
    Zadaniem tego śpiewnika jest wyświetlać wszystko, co będzie Ci potrzebne podczas mszy:
    pieśni, części stałe, wszystko w ładnym i zgrabnym porządku.
</p>

@guest
<x-shipyard.app.h lvl="2" :icon="model_icon('users')">Masz konto?</x-shipyard.app.h>
<p>Za pomocą konta możesz tworzyć własne zestawy i dodawać nowe pieśni oraz części stałe.</p>
<p>Przejdź do logowania/rejestracji przyciskiem na dole strony.</p>
@endguest

<x-shipyard.app.h lvl="2" icon="cached">Pamięć podręczna</x-shipyard.app.h>
<p>
    Niektóre funkcje szpiewnika (zestawy i pieśni) mogą posiadać przestarzałe funkcje i nie działać poprawnie w toku aktualizacji.
    Jeżeli coś nie działa tak, jak powinno, spróbuj odświeżyć pamięć podręczną przyciskiem poniżej.
    Jeśli to nie pomoże, <a href="mailto:contact@wpww.pl">daj mi znać</a>.
</p>
<div class="flex right center">
    <div class="button" onclick="location.reload(true);">Odśwież pamięć podręczną</div>
</div>

@endsection
