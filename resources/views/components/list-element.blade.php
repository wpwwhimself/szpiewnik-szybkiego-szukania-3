@props([
    "present",
    "edit",
    "clearanceForEdit" => 0,
])

<div class="list-element">
    <span {{ $attributes->merge(["class" => "main"]) }}>
        {{ $slot }}
    </span>
    <div class="hover">
        <a href="{{ $present }}">Wyświetl</a>
        @if (Auth::user()?->clearance->id >= $clearanceForEdit)
        <a href="{{ $edit }}">Edytuj</a>
        @endif
    </div>
</div>
