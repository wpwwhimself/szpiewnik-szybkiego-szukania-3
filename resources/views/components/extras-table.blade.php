@props([
    "model",
])

@php
$is_set = $model instanceof \App\Models\Set;

$mass_order = collect(json_decode((new \App\Http\Controllers\DataModController)->massOrder()->content()))
    ->pluck("label", "value")
    ->toArray();
@endphp

<p class="ghost">
    Aby dodać pieśń, w pole <em>Element</em> wpisz jej tytuł.
    Aby dodać część stałą, zacznij wpisywanie od <code>!</code>.
    Aby dodać element specjalny, zacznij wpisywanie od <code>x</code>.
</p>

{{-- intercept choices setup --}}
<div class="hidden">
    <x-select name="before[]" label="" :options="[]" :empty-option="true" />
</div>

<table>
    <thead>
        <tr>
            @if ($is_set) <th>Źródło</th> @endif
            <th>Element</th>
            <th>Etykieta</th>
            <th>Przed</th>
            <th>Zastąp</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="{{ 4 + $is_set }}">
                <div class="flex right spread and-cover">
                    <x-shipyard.ui.button
                        label="Dodaj"
                        icon="plus"
                        action="none"
                        onclick="addExtraRow()"
                        class="tertiary"
                    />
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="{{ 4 + $is_set }}">
                <div id="song-autocomplete" class="flex right center wrap"></div>
            </td>
        </tr>
        <tr id="row-adder">
            <input type="hidden" name="extraId[]" />
            @if ($is_set) <td>Msza</td> @endif
            <td><x-input type="text" name="song[]" label="" onkeyup="songAutocomplete(this)" /></td>
            <td><x-input type="text" name="label[]" label="" /></td>
            <td><x-select name="before[]" label="" :options="$mass_order" :empty-option="true" /></td>
            <td class="input-container">
                <input type="checkbox" onchange="extraReplaceCheck(this)" />
                <input type="hidden" name="replace[]" value="0" />
            </td>
        </tr>
    @foreach ($model->extras as $extra)
        <tr>
            <input type="hidden" name="extraId[]" value="{{ $extra->id }}" />
            @if ($is_set) <td>Msza</td> @endif
            <td><x-input type="text" name="song[]" label="" value="{{ $extra->name }}" onkeyup="songAutocomplete(this)" /></td>
            <td><x-input type="text" name="label[]" label="" value="{{ $extra->label }}" /></td>
            <td><x-select name="before[]" label="" value="{{ $extra->before }}" :options="$mass_order" :empty-option="true" /></td>
            <td class="input-container">
                <input type="checkbox" onchange="extraReplaceCheck(this)" {{ $extra->replace ? 'checked' : '' }} />
                <input type="hidden" name="replace[]" value="{{ $extra->replace }}" />
            </td>
        </tr>
    @endforeach
        <tr id="row-spacer"></tr>
    @if ($is_set)
    @foreach ($model->formulaData->extras as $extra)
        <tr class="ghost">
            <td>Formuła</td>
            <td><span>{{ $extra->name }}</span></td>
            <td><span>{{ $extra->label }}</span></td>
            <td><span>{{ $mass_order[$extra->before] ?? "—" }}</span></td>
            <td>
                <input type="checkbox" name="" {{ $extra->replace ? 'checked' : '' }} disabled />
            </td>
        </tr>
    @endforeach
    @endif
    </tbody>
</table>

<script>
function addExtraRow() {
    const rowAdder = document.getElementById("row-adder");
    const clone = rowAdder.cloneNode(true);
    rowAdder.parentNode.insertBefore(clone, document.getElementById("row-spacer"));
    clone.removeAttribute("id");
}
function extraReplaceCheck(el) {
    el.nextElementSibling.value = +el.checked;
}
let songAutocompleteTimeout = null;
function songAutocomplete(el) {
    clearTimeout(songAutocompleteTimeout);
    songAutocompleteTimeout = setTimeout(() => {
        const title = el.value;
        const hintBox = document.getElementById("song-autocomplete");

        fetch("{{ route('get-song-autocomplete') }}", {
            method: "post",
            body: JSON.stringify({
                title: title,
            }),
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
            }
        }).then(res => res.json()).then(data => {
            hintBox.innerHTML = "";
            window.songAutocompleteBox = el;
            data.forEach(title => {
                hintBox.innerHTML += `<span class="interactive" onclick="songAutocompleteInsert('${title}')">${title}</span>`;
            });
        }).catch(err => console.error(err));
    }, 0.5e3);
}
function songAutocompleteInsert(title) {
    window.songAutocompleteBox.value = title;
    window.songAutocompleteBox = null;
    document.getElementById("song-autocomplete").innerHTML = "";
}
</script>
