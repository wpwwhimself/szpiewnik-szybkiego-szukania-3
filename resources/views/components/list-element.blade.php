@props([
    "present",
    "edit",
    "roleForEdit" => null,
])

<div class="list-element">
    <a href="{{ $present }}">
        <span {{ $attributes->merge(["class" => "main"]) }}>
            {{ $slot }}
        </span>
    </a>
</div>
