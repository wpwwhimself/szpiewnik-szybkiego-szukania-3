@props([
    "matches",
])

@forelse ($matches as $match)
<x-shipyard::app.model.tile :model="\App\Models\Ordinarius::firstWhere([
    ['color_code', $match['color_code']],
    ['part', $match['part']],
])">
    <x-slot:actions>
        <x-shipyard::ui.button
            icon="chevron-double-right"
            label="Przejdź"
            :action="route('ordinarius-present', ['color' => $match['color_code']])"
        />
    </x-slot:actions>
</x-shipyard::app.model.tile>
@empty
Brak wyników
@endforelse
