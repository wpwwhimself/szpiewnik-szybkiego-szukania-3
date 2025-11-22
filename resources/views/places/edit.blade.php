@extends("layouts.shipyard.admin")
@section("title", $place ?? "Nowe miejsce")
@section("subtitle", "Edycja miejsca")

@section("content")

<x-shipyard.app.form method="post" :action="route('place-edit')">
    <input type='hidden' name="old_name" value="{{ $place->name }}" />

<x-shipyard.app.section title="Podstawowe informacje" :icon="model_icon('places')">
    <x-shipyard.ui.field-input :model="$place" field-name="name" />
    <x-shipyard.ui.field-input :model="$place" field-name="notes" rows="10" />
</x-shipyard.app.section>

<x-shipyard.app.section title="Zmiany" icon="details">
    <x-extras-table :model="$place" />
</x-shipyard.app.section>

    <x-slot:actions>
        <x-shipyard.ui.button
            label="Zatwierdź i wróć"
            icon="check"
            action="submit"
            name="action"
            value="update"
            class="primary"
        />
        <x-shipyard.ui.button
            label="Usuń"
            icon="delete"
            action="submit"
            name="action"
            value="delete"
            class="danger"
        />
        @if (Auth::user()->hasRole("technical"))
        <x-shipyard.ui.button
            label="Historia zmian"
            icon="history"
            :action="route('changes', ['type' => 'place', 'id' => $place->name])"
            target="_blank"
        />
        @endif
    </x-slot:actions>
</x-shipyard.app.form>

@endsection
