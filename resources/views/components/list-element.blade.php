@props([
    "present",
    "edit",
    "roleForEdit" => null,
])

<div class="list-element">
    <span {{ $attributes->merge(["class" => "main"]) }}>
        {{ $slot }}
    </span>
    <div class="hover">
        <a href="{{ $present }}">Wyświetl</a>
        @if ($roleForEdit && Auth::user()?->hasRole($roleForEdit))
        <a href="{{ $edit }}">Edytuj</a>
        @endif
    </div>
</div>
