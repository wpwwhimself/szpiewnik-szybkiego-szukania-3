@props([
    "sets",
])

@php
$color_ordering = App\Models\OrdinariusColor::ordered()->pluck("ordering", "name");
$ordered_sets = $sets->groupBy("color")->sortBy(fn ($clr, $k) => $color_ordering[$k]);
@endphp

<div class="flex right center middle ghost">
    @foreach ($ordered_sets as $color => $sets_of_color)
    <span style="color: {{ $sets_of_color->first()->colorData->display_color }};">⬤</span>
    <x-dot-counter :number="$sets_of_color->count()" />
    @endforeach
</div>
