@extends("layouts.shipyard.admin")
@section("title", $set ?? "Nowy zestaw")
@section("subtitle", "Edycja zestawu")

@section("content")

<x-shipyard.app.form method="post" :action="route('set-edit')">
    <input type='hidden' name="id" value="{{ $set->id }}" />

<x-shipyard.app.section title="Podstawowe informacje" :icon="model_icon('sets')">
    <div class="grid" style="--col-count: 2;">
        <x-shipyard.ui.field-input :model="$set" field-name="name" />
        <x-shipyard.ui.connection-input :model="$set" connection-name="formulaData" />
        <x-shipyard.ui.connection-input :model="$set" connection-name="colorData" />
        <x-shipyard.ui.field-input :model="$set" field-name="public" />
    </div>
</x-shipyard.app.section>

<x-shipyard.app.section title="Pieśni" :icon="model_icon('songs')">
    <div class="flex down center">
        <x-input type="text" name="song-adder" label="Dopisz pieśń" onkeyup="songAdderAutocomplete(this)" />
        <div id="song-adder-autocomplete" class="flex right center wrap"></div>
        <span id="song-adder-duplicate" class="alert-color error hidden"></span>
    </div>

    <div class="grid" style="--col-count: 5;">
    @foreach ([
        "sIntro" => "Wejście",
        "sOffer" => "Przygotowanie Darów",
        "sCommunion" => "Komunia",
        "sAdoration" => "Uwielbienie",
        "sDismissal" => "Zakończenie",
    ] as $code => $label)
        @php $i ??= -1; $i++ @endphp
        <div class="flex down center middle">
            <label class="accent secondary">{{ $label }}</label>
            <div class="flex right center">
                <x-shipyard.ui.button
                    pop="Dodaj powyższą pieśń do listy poniżej"
                    icon="plus"
                    action="none"
                    class="tertiary song-adder-trigger"
                    :data-elem="$code"
                />
                <x-shipyard.ui.button
                    pop="Zaproponuj pieśń na to miejsce"
                    icon="dice-3"
                    action="none"
                    class="tertiary song-randomizer-trigger"
                    :data-elem="$i"
                />
            </div>
            <div class="flex down center">
            @foreach (explode("\n", $set->{$code}) as $song)
                <a href="#/" class="song-adder-song">{{ $song }}</a>
            @endforeach
            </div>
            <input class="set_songs" type="hidden" name="{{ $code }}" value="{{ $set->{$code} }}">
        </div>
    @endforeach
    </div>
</x-shipyard.app.section>

<x-shipyard.app.section title="Psalm i aklamacja" icon="script">
    <div class="grid" style="--col-count: 2;">
        <x-input type="TEXT" name="pPsalm" label="Psalm" value="{!! $set->pPsalm !!}" rows="20" />
        <x-input type="TEXT" name="pAccl" label="Aklamacja" value="{!! $set->pAccl !!}" rows="20" />
    </div>
</x-shipyard.app.section>

<x-shipyard.app.section title="Zmiany" icon="details">
    <p class="ghost">
        Aby dodać pieśń, w pole <em>Element</em> wpisz jej tytuł.
        Aby dodać część stałą, zacznij wpisywanie od <code>!</code>.
        Aby dodać element specjalny, zacznij wpisywanie od <code>x</code>.
    </p>
    <table>
        <thead>
            <tr>
                <th>Źródło</th>
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
            let songAutocompleteTimeout = null;
            function songAutocomplete(el){
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
                            hintBox.innerHTML += `<span class="safety clickable" onclick="songAutocompleteInsert('${title}')">${title}</span>`;
                        });
                    }).catch(err => console.error(err));
                }, 0.5e3);
            }
            function songAutocompleteInsert(title){
                window.songAutocompleteBox.value = title;
                window.songAutocompleteBox = null;
                document.getElementById("song-autocomplete").innerHTML = "";
            }
            </script>
            <tr>
                <td colspan="5">
                    <div class="button" onclick="addExtraRow()">Dodaj</div>
                </td>
            </tr>
            <tr>
                <td colspan="5">
                    <div id="song-autocomplete" class="flex right center wrap"></div>
                </td>
            </tr>
            <tr id="row-adder">
                <td>Msza<input type="hidden" name="extraId[]" /></td>
                <td><input type="text" name="song[]" onkeyup="songAutocomplete(this)" /></td>
                <td><input type="text" name="label[]" /></td>
                <td><x-select name="before[]" label="" :options="$mass_order" :empty-option="true" /></td>
                <td>
                    <input type="checkbox" onchange="extraReplaceCheck(this)" />
                    <input type="hidden" name="replace[]" value="0" />
                </td>
            </tr>
        @foreach ($set->extras as $extra)
            <tr>
                <td>Msza<input type="hidden" name="extraId[]" value="{{ $extra->id }}" /></td>
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
        @foreach ($set->formulaData->extras as $extra)
            <tr class="ghost">
                <td>Formuła</td>
                <td><input type="text" name="" value="{{ $extra->name }}" disabled /></td>
                <td><input type="text" name="" value="{{ $extra->label }}" disabled /></td>
                <td><x-select name="" label="" value="{{ $extra->before }}" :options="$mass_order" :empty-option="true" :disabled="true" /></td>
                <td>
                    <input type="checkbox" name="" {{ $extra->replace ? 'checked' : '' }} disabled />
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
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
            :action="route('changes', ['type' => 'set', 'id' => $set->id])"
            target="_blank"
        />
        @endif
    </x-slot:actions>
</x-shipyard.app.form>

<script defer>
function songAdderAutocomplete(el){
    const title = el.value;
    const hintBox = document.getElementById("song-adder-autocomplete");

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
            hintBox.innerHTML += `<span class="safety clickable" onclick="songAdderAutocompleteInsert('${title}')">${title}</span>`;
        });
    }).catch(err => console.error(err));
}
function songAdderAutocompleteInsert(title){
    document.getElementById("song-adder").value = title;
    document.getElementById("song-adder-autocomplete").innerHTML = "";
}
function songAdd(ev){
    ev.preventDefault();
    const title = document.getElementById("song-adder").value;
    const song_list = document.getElementById("song-adder");
    const input = ev.target.parentElement.parentElement.children[3];

    if(title != ""){
        //add song to element list
        const list = ev.target.parentElement.parentElement.children[2];
        const newSong = document.createElement("a");
        newSong.textContent = title;
        newSong.href = "#/";
        newSong.classList.add("song-adder-song");
        newSong.addEventListener("click", songRemove);
        list.appendChild(newSong);
        //add song to input
        input.value += "\n"+title;
    }
    song_list.value = "";
    song_list.dispatchEvent(new Event('change'));
    console.log(input.value);
}
function songRemove(ev){
    ev.preventDefault();
    const title = ev.target.textContent.trim();
    //remove song from input
    const input = ev.target.parentElement.parentElement.children[3];
    input.value = input.value.split("\n").filter(el => el != title).join("\n");
    //remove song from the list
    ev.target.remove();
    console.log(input.value);
}
function songRandom(ev){
    ev.preventDefault();
    const part = ev.target.getAttribute("data-elem");
    const formula = document.getElementById("formula").value;

    fetch("{{ route('get-song-random') }}", {
        method: "post",
        body: JSON.stringify({
            part: part,
            formula: formula,
        }),
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
        }
    }).then(res => res.json()).then(data => {
        document.getElementById("song-adder").value = data.title;
    }).catch(err => {
        console.error(err);
        document.getElementById("song-adder").value = "";
    });
}
function songCheckDuplicates(ev){
    const song_to_test = ev.target.value;
    const last_set = {!! json_encode($last_set) !!};
    const czsts = {
        "sIntro": "Wejście",
        "sOffer": "Przygotowanie Darów",
        "sCommunion": "Komunię",
        "sAdoration": "Uwielbienie",
        "sDismissal": "Wyjście"
    };
    const alert_box = document.getElementById("song-adder-duplicate");
    let duplicates_on = null;

    for(let [code, transl] of Object.entries(czsts)){
        if(last_set[code].match(song_to_test)){
            duplicates_on = [code, "last"];
            break;
        }else if(document.querySelector(`.set_songs[name="${code}"]`).value.match(song_to_test)){
            duplicates_on = [code, "current"];
            break;
        }
    }

    if(duplicates_on == null || song_to_test == ""){
        alert_box.innerHTML = "";
        alert_box.classList.add("hidden");
    }else{
        alert_box.innerHTML =
            (duplicates_on[1] == "last")
            ? `Ta pieśń była przez Ciebie grana ostatnio na ${czsts[duplicates_on[0]]}`
            : `Ta pieśń jest już w tej mszy na ${czsts[duplicates_on[0]]}`;
        alert_box.classList.remove("hidden");
    }
}

document.querySelectorAll(".song-adder-trigger").forEach(el => el.addEventListener("click", songAdd));
document.querySelectorAll(".song-adder-song").forEach(el => el.addEventListener("click", songRemove));
document.querySelectorAll(".song-randomizer-trigger").forEach(el => el.addEventListener("click", songRandom));
document.querySelector("#song-adder").addEventListener("change", songCheckDuplicates);
</script>

@endsection
