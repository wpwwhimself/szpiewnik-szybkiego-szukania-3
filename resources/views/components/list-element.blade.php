@props([
    "present",
    "edit",
])

<div class="list-element">
    <span {{ $attributes->merge(["class" => "main"]) }}>
        {{ $slot }}
    </span>
    <div class="hover">
        <a href="{{ $present }}">Wyświetl</a>
        <a href="{{ $edit }}">Edytuj</a>
    </div>
</div>
