@extends("layout")

@section("content")

<h1>Parametry formuły</h1>
<form method="post" action="{{ route('formula-edit') }}">
    @csrf
    <input type='hidden' name="old_name" value="{{ $formula->name }}" />
    <div class="flex-right center wrap">
        <x-input type="text" name="name" label="Nazwa" value="{{ $formula->name }}" />
    </div>

    <h2>Zmiany</h2>
    <p class="ghost">
        Aby dodać pieśń, w pole <em>Element</em> wpisz jej tytuł.
        Aby dodać część stałą, zacznij wpisywanie od <code>!<code>.
        Aby dodać element specjalny, zacznij wpisywanie od <code>x</code>.
    </p>
    <table>
        <thead>
            <tr>
                <th>Element</th>
                <th>Etykieta</th>
                <th>Przed</th>
                <th>Zastąp</th>
            </tr>
        </thead>
        <tbody>
            <script>
            function addExtraRow(){
                const rowAdder = document.getElementById("row-adder");
                const clone = rowAdder.cloneNode(true);
                rowAdder.parentNode.insertBefore(clone, document.getElementById("row-spacer"));
                clone.removeAttribute("id");
            }
            function extraReplaceCheck(el){
                el.nextElementSibling.value = +el.checked;
            }
            function songAutocomplete(el){
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
                        hintBox.innerHTML += `<span class="light-button clickable" onclick="songAutocompleteInsert('${title}')">${title}</span>`;
                    });
                }).catch(err => console.error(err));
            }
            function songAutocompleteInsert(title){
                window.songAutocompleteBox.value = title;
                window.songAutocompleteBox = null;
                document.getElementById("song-autocomplete").innerHTML = "";
            }
            </script>
            <tr>
                <td colspan="4">
                    <div class="button" onclick="addExtraRow()">Dodaj</div>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <div id="song-autocomplete" class="flex-right center wrap"></div>
                </td>
            </tr>
            <tr id="row-adder">
                <input type="hidden" name="extraId[]" />
                <td><input type="text" name="song[]" onkeyup="songAutocomplete(this)" /></td>
                <td><input type="text" name="label[]" /></td>
                <td><x-select name="before[]" label="" :options="$mass_order" :empty-option="true" /></td>
                <td>
                    <input type="checkbox" onchange="extraReplaceCheck(this)" />
                    <input type="hidden" name="replace[]" value="0" />
                </td>
            </tr>
        @foreach ($formula->extras as $extra)
            <tr>
                <input type="hidden" name="extraId[]" value="{{ $extra->id }}" />
                <td><input type="text" name="song[]" value="{{ $extra->name }}" onkeyup="songAutocomplete(this)" /></td>
                <td><input type="text" name="label[]" value="{{ $extra->label }}" /></td>
                <td><x-select name="before[]" label="" value="{{ $extra->before }}" :options="$mass_order" :empty-option="true" /></td>
                <td>
                    <input type="checkbox" onchange="extraReplaceCheck(this)" {{ $extra->replace ? 'checked' : '' }} />
                    <input type="hidden" name="replace[]" value="{{ $extra->replace }}" />
                </td>
            </tr>
        @endforeach
            <tr id="row-spacer"></tr>
        </tbody>
    </table>

    <div class="flex-right stretch">
        <x-button type="submit" name="action" value="update">Zatwierdź i wróć</x-button>
        <x-button type="submit" name="action" value="delete">Usuń</x-button>
    </div>
</form>

@endsection
