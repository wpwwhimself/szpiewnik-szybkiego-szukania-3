@extends("layouts.shipyard.admin")
@section("title", $formula ?? "Nowa formuła")
@section("subtitle", "Edycja formuły")

@section("content")

<x-shipyard.app.form method="post" :action="route('formula-edit')">
    <input type='hidden' name="old_name" value="{{ $formula->name }}" />

<x-shipyard.app.section title="Podstawowe informacje" :icon="model_icon('formulas')">
    <div class="grid" style="--col-count: 2;">
        <x-shipyard.ui.field-input :model="$formula" field-name="name" />
    </div>
</x-shipyard.app.section>

<x-shipyard.app.section title="Zmiany" icon="details">
    <x-extras-table :model="$formula" />
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
    </x-slot:actions>
</x-shipyard.app.section>

@endsection
