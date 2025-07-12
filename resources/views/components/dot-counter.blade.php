@props([
    "number" => 0,
])

@php
$dots = ["⠀", "⠄", "⠆", "⠦", "⠧", "⠷", "⠿"];
@endphp

<span title="{{ $number }}">
    {{ str_repeat($dots[6], floor($number / 6)) . $dots[$number % 6]; }}
</span>
