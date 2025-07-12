@props([
    "sets",
])

<div class="flex-right center middle ghost">
    @foreach ($sets->groupBy("color")->sortKeys() as $color => $sets_of_color)
    <span style="color: {{ $sets_of_color->first()->colorData->display_color }};">⬤</span>
    <x-dot-counter :number="$sets_of_color->count()" />
    @endforeach
</div>
