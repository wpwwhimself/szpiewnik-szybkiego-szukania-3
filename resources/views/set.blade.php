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

    <h2>Pieśni</h2>
    <div class="flex-right center wrap">
        <x-select name="song-adder" label="Pieśń" :empty-option="true" :options="$songs" />
    </div>
    <div class="grid-5">
    @foreach ([
        "sIntro" => "Wejście",
        "sOffer" => "Przygotowanie Darów",
        "sCommunion" => "Komunia",
        "sAdoration" => "Uwielbienie",
        "sDismissal" => "Zakończenie",
    ] as $code => $label)
        <div class="flex-down center">
            <x-button class="song-adder-trigger" data-elem="{{ $code }}">↓</x-button>
            <label>{{ $label }}</label>
            <div class="flex-down center">
            @foreach (explode("\n", $set->{$code}) as $song)
                <a href="#/" class="song-adder-song">{{ $song }}</a>
            @endforeach
            </div>
            <input type="hidden" name="{{ $code }}" value="{{ $set->{$code} }}">
        </div>
    @endforeach
    </div>
    <script defer>
    function songAdd(ev){
        ev.preventDefault();
        const title = document.getElementById("song-adder").value;
        if(title != ""){
            //add song to element list
            const list = ev.target.parentElement.children[2];
            const newSong = document.createElement("a");
            newSong.textContent = title;
            newSong.href = "#/";
            newSong.classList.add("song-adder-song");
            newSong.addEventListener("click", songRemove);
            list.appendChild(newSong);
            //add song to input
            const code = ev.target.getAttribute("data-elem");
            document.querySelector(`input[name=${code}]`).value += "\n"+title;
        }
        document.getElementById("song-adder").value = "";
    }
    function songRemove(ev){
        ev.preventDefault();
        const title = ev.target.textContent;
        //remove song from input
        const input = ev.target.parentElement.parentElement.children[3];
        input.value = input.value.split("\n").filter(el => el != title).join("\n");
        //remove song from the list
        ev.target.remove();
    }

    document.querySelectorAll(".song-adder-trigger").forEach(el => el.addEventListener("click", songAdd));
    document.querySelectorAll(".song-adder-song").forEach(el => el.addEventListener("click", songRemove));
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
        @foreach ($set->extras as $extra)
            <tr>
                <td>Msza</td>
                <td>{{ $extra->name }}</td>
                <td>{{ $extra->before }}</td>
                <td><input type="checkbox" name="" {{ $extra->replace ? 'checked' : '' }} /></td>
            </tr>
        @endforeach
        @foreach ($set->formulaData->extras as $extra)
            <tr class="ghost">
                <td>Formuła</td>
                <td>{{ $extra->name }}</td>
                <td>{{ $extra->before }}</td>
                <td><input type="checkbox" name="" {{ $extra->replace ? 'checked' : '' }} /></td>
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
