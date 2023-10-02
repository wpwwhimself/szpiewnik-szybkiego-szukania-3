@extends("layout")

@section("content")

<h1>Parametry mszy</h1>
<form method="post" action="{{ route('set-edit') }}">
    @csrf
    <input type='hidden' name="id" value="{{ $set->id }}" />
    <div class="flex-right center wrap">
        <x-input type="text" name="name" label="Nazwa" value="{{ $set->name }}" />
        <x-select name="formula" label="Formuła" value="{{ $set->formula }}" :options="$formulas" />
        <x-select name="color" label="Kolor cz.st." value="{{ $set->color }}" :options="$colors" />
        <x-input type="text" name="user_id" label="Twórca" value="{{ $set->user->name }}" :disabled="true" />
        <x-input type="checkbox" name="public" label="Publiczny" value="{{ $set->public }}" :disabled="$set->user_id !== Auth::id()" />
    </div>

    <script defer>
    const formula_selector = document.getElementById("formula");
    function fill_song_list(formula){
        fetch("{{ route('get-song-sugg-list') }}", {
            method: "post",
            body: JSON.stringify({
                formula: formula,
            }),
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
            }
        }).then(res => res.json()).then(data => {
            const select = document.getElementById("song-adder");

            //clear the list
            select.innerHTML = "<option value=''></option>";

            //fill with matching songs
            for(const item of data){
                select.insertAdjacentHTML("beforeend", `
                    <option value="${item.title}" data-item="${item.preferences}">${item.title}</option>
                `);
            }
        }).catch(err => console.error(err));
    }

    fill_song_list(formula_selector.value);
    formula_selector.addEventListener("change", (ev) => fill_song_list(ev.target.value));
    </script>

    <h2>Pieśni</h2>
    <div class="flex-down center wrap">
        <x-select name="song-adder" label="Pieśń" :empty-option="true" :options="[]" />
        <span id="song-adder-duplicate" class="alert-color error hidden"></span>
    </div>
    <div class="grid-5">
    @foreach ([
        "sIntro" => "Wejście",
        "sOffer" => "Przygotowanie Darów",
        "sCommunion" => "Komunia",
        "sAdoration" => "Uwielbienie",
        "sDismissal" => "Zakończenie",
    ] as $code => $label)
        @php $i ??= -1; $i++ @endphp
        <div class="flex-down center">
            <div class="flex-right center">
                <x-button class="song-adder-trigger" data-elem="{{ $code }}" title="Wybierz pieśń">↓</x-button>
                <x-button class="song-randomizer-trigger" data-elem="{{ $i }}" title="Zaproponuj pieśń">?</x-button>
            </div>
            <label>{{ $label }}</label>
            <div class="flex-down center">
            @foreach (explode("\n", $set->{$code}) as $song)
                <a href="#/" class="song-adder-song">{{ $song }}</a>
            @endforeach
            </div>
            <input class="set_songs" type="hidden" name="{{ $code }}" value="{{ $set->{$code} }}">
        </div>
    @endforeach
    </div>
    <script defer>
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
        const code = ev.target.getAttribute("data-elem");
        const song_list = document.getElementById("song-adder");

        let random_song;
        while(true){
            random_song = song_list.options[Math.floor(Math.random() * song_list.length)];
            if(random_song.getAttribute("data-item").split("/")[code] == 1){
                break;
            }
        }
        //select random song
        song_list.value = random_song.value;
        song_list.dispatchEvent(new Event('change'));
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

    <h2>Psalm i aklamacja</h2>
    <div class="grid-2">
        <x-input type="TEXT" name="pPsalm" label="Psalm" value="{!! $set->pPsalm !!}" />
        <x-input type="TEXT" name="pAccl" label="Aklamacja" value="{!! $set->pAccl !!}" />
    </div>

    <h2>Zmiany</h2>
    <table>
        <thead>
            <tr>
                <th>Źródło</th>
                <th>Element</th>
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
                <td>Msza<input type="hidden" name="extraId[]" /></td>
                <td><input type="text" name="song[]" onkeyup="songAutocomplete(this)" /></td>
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
                <td><x-select name="" label="" value="{{ $extra->before }}" :options="$mass_order" :empty-option="true" :disabled="true" /></td>
                <td>
                    <input type="checkbox" name="" {{ $extra->replace ? 'checked' : '' }} disabled />
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="flex-right stretch">
        <x-button type="submit" name="action" value="update">Zatwierdź i wróć</x-button>
        <x-button type="submit" name="action" value="delete">Usuń</x-button>
    </div>
</form>

@endsection
