@extends("layout")

@section("content")

<h1>Parametry pieśni</h1>
<form method="post" action="{{ route('song-edit') }}">
  @csrf
  <script src="{{ asset("js/note-transpose.js") }}"></script>

  <div class="grid-3">
    <input type="hidden" name="old_title" value="{{ $song->title }}" />
    <x-input type="text" name="title" label="Tytuł" value="{{ $song->title }}" />
    <x-select name="song_category_id" label="Grupa" value="{{ $song->song_category_id }}" :options="$categories" />
    <x-input type="text" name="category_desc" label="Mini-opis" value="{{ $song->category_desc }}" />
    <x-input type="text" name="number_preis" label="Numer w projektorze Preis" value="{{ $song->number_preis }}" />
    <x-input type="text" name="key" label="Tonacja" value="{{ $song->key }}" />
    <div class="inputContainer">
      <x-input type="text" name="pref5" label="Uwagi" value="{{ $prefs['other'] ?: '' }}" />
      <div class="flex-right center">
        @foreach ([
          "sIntro" => "Wejście",
          "sOffer" => "Dary",
          "sCommunion" => "Komunia",
          "sAdoration" => "Uwielbienie",
          "sDismissal" => "Zakończenie",
        ] as $code => $label)
          <x-input type="checkbox" name="{{ $code }}" label="{{ $label }}" value="{{ $prefs[$code] }}" />
        @endforeach
      </div>
    </div>
  </div>
  <div class="grid-2">
    <div>
      <div id="lyrics-variant-big-container">
      @foreach ($song->lyrics_variants as $var_no => $lyrics)
        <div class="variant-container">
          <x-input type="TEXT" name="lyrics[]" :var-no="$var_no" label="Tekst" value="{!! $lyrics !!}" />
          <div class="flex-right stretch">
            <x-button id="remove-lyrics-variant" :var-no="$var_no">Usuń wariant</x-button>
          </div>
          <hr>
        </div>
        <script>
          document.querySelector("#remove-lyrics-variant[var-no='{{ $var_no }}']").addEventListener("click", (e) => { e.preventDefault(); e.target.closest(".variant-container").remove(); })
        </script>
      @endforeach
      </div>
      <div class="flex-right stretch">
        <x-button id="addLyricsVariantButton">Dodaj wariant</x-button>
      </div>
    </div>
    <div>
      <div id="variant-big-container">
      @foreach ($song->sheet_music_variants as $var_no => $notes)
        <div class="variant-container">
          <x-input type="TEXT" name="sheet_music[]" :var-no="$var_no" label="Nuty" value="{!! $notes !!}" />
          <div id="note-transpose-{{ $var_no }}" class="flex-right center wrap">
            <x-button name="up">♪+</x-button>
            <x-button name="down">♪-</x-button>
          </div>
          <div id="sheet-music-container-{{ $var_no }}"></div>
          <div class="flex-right stretch">
            <x-button id="remove-variant" :var-no="$var_no">Usuń wariant</x-button>
          </div>
          <hr>
        </div>
        <script>
        document.querySelector("#note-transpose-{{ $var_no }} button[name=up]").addEventListener("click", (e) => { e.preventDefault(); Hoch(document.querySelector("textarea[name^='sheet_music'][var-no='{{ $var_no }}']"), e); });
        document.querySelector("#note-transpose-{{ $var_no }} button[name=down]").addEventListener("click", (e) => { e.preventDefault(); Runter(document.querySelector("textarea[name^='sheet_music'][var-no='{{ $var_no }}']"), e); });
        document.querySelector("#remove-variant[var-no='{{ $var_no }}']").addEventListener("click", (e) => { e.preventDefault(); e.target.closest(".variant-container").remove(); })
        </script>
      @endforeach
      </div>
      <div class="flex-right stretch">
        <x-button id="addVariantButton">Dodaj wariant</x-button>
      </div>

      <p class="ghost">
        Zapis nutowy korzysta z formatu ABC. Dokumentacja jest <a href="https://abcnotation.com/wiki/abc:standard:v2.1" target="_blank">tutaj</a>.
      </p>
    </div>
  </div>

  @if (Auth::user()?->clearance->id >= 2)
  <div class="flex-right stretch">
    <x-button type="submit" name="action" value="update">Zatwierdź i wróć</x-button>
    <x-button type="submit" name="action" value="delete">Usuń</x-button>
  </div>
  @endif
</form>

<script src="{{ asset("js/abcjs-basic-min.js") }}"></script>
<script defer>
  function render(noteInput){
    const var_no = +noteInput.getAttribute("var-no").replace(/.*\[(\d+)\]/, "$1");
    const sheet = noteInput.value;
    ABCJS.renderAbc(
      "sheet-music-container-" + var_no,
      sheet,
      {
        responsive: "resize",
        // germanAlphabet: true, // zmienia B -> H w akordach, bezużyteczne
      }
    );
  }
  document.querySelectorAll("textarea[name^='sheet_music']").forEach(box => {
    render(box);
    box.addEventListener("keyup", () => render(box));
  });

  function addVariant(ev){
    ev.preventDefault();
    const newVariant = document.querySelector("#variant-big-container .variant-container:first-of-type").cloneNode(true);
    const new_var_no = document.querySelectorAll(".variant-container").length;
    const newNoteInput = newVariant.querySelector("textarea[name^='sheet_music']");
    newNoteInput.value = "";
    newNoteInput.setAttribute("var-no", new_var_no);
    newVariant.querySelector("#remove-variant").setAttribute("var-no", new_var_no);

    newVariant.querySelector("[id^='sheet-music-container']").id = `sheet-music-container-${new_var_no}`;
    newVariant.querySelector("[id^='note-transpose']").id = `note-transpose-${new_var_no}`;

    document.getElementById("variant-big-container").appendChild(newVariant);

    render(newNoteInput);
    newNoteInput.addEventListener("keyup", () => render(newNoteInput));
    newVariant.querySelector(`#note-transpose-${new_var_no} button[name=up]`).addEventListener("click", (e) => { e.preventDefault(); Hoch(document.querySelector(`textarea[name^='sheet_music'][var-no='${new_var_no}']`), e); });
    newVariant.querySelector(`#note-transpose-${new_var_no} button[name=down]`).addEventListener("click", (e) => { e.preventDefault(); Runter(document.querySelector(`textarea[name^='sheet_music'][var-no='${new_var_no}']`), e); });
    newVariant.querySelector(`#remove-variant`).addEventListener("click", (e) => { e.preventDefault(); e.target.closest(".variant-container").remove(); })
  }
  document.getElementById("addVariantButton").addEventListener("click", addVariant);

  function addLyricsVariant(ev){
    ev.preventDefault();
    const newVariant = document.querySelector("#lyrics-variant-big-container .variant-container:first-of-type").cloneNode(true);
    const new_var_no = document.querySelectorAll(".variant-container").length;
    const newLyricsInput = newVariant.querySelector("textarea[name^='lyrics']");
    newLyricsInput.value = "";
    newLyricsInput.setAttribute("var-no", new_var_no);
    newVariant.querySelector("#remove-lyrics-variant").setAttribute("var-no", new_var_no);

    document.getElementById("lyrics-variant-big-container").appendChild(newVariant);

    render(newLyricsInput);
    newVariant.querySelector(`#remove-lyrics-variant`).addEventListener("click", (e) => { e.preventDefault(); e.target.closest(".variant-container").remove(); })
  }
  document.getElementById("addLyricsVariantButton").addEventListener("click", addLyricsVariant);
</script>

@endsection
