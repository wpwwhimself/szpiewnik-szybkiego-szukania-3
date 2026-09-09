@props([
    "model",
])

@foreach ($model->sheet_music_variants as $i => $notes)
<x-shipyard::ui.abc-preview
    :name="implode('_', ['ordinarius_preview', $model->color_code, $model->part, $i])"
    :value="$notes"
/>
@endforeach
