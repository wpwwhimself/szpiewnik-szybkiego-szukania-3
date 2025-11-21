@extends("layouts.shipyard.admin")
@section("title", $song ?? "Nowa pieśń")
@section("subtitle", "Edycja pieśni")

@section("content")

<x-shipyard.app.form method="post" :action="route('song-edit')">
  <script src="{{ asset("js/note-transpose.js") }}"></script>
  <input type="hidden" name="old_title" value="{{ $song->title }}" />

<x-shipyard.app.section title="Podstawowe informacje" :icon="model_icon('songs')">
  <div class="grid" style="--col-count: 2;">
    <x-shipyard.ui.field-input :model="$song" field-name="title" />
    <x-shipyard.ui.connection-input :model="$song" connection-name="category" />
    <x-input type="text" name="category_desc" label="Kategoria (ze śpiewnika)" value="{{ $song->category_desc }}" />
    <x-input type="text" name="number_preis" label="Numer w projektorze Preis" value="{{ $song->number_preis }}" />
    <x-input type="text" name="key" label="Tonacja" value="{{ $song->key }}" />
    <div class="input-container">
      <x-input type="text" name="pref5" label="Uwagi" value="{{ $prefs['other'] ?: '' }}" />
      <div class="flex right center">
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
</x-shipyard.app.section>

<x-shipyard.app.section title="Teksty i nuty" icon="playlist-music">
  <div class="grid" style="--col-count: 2;">
    <div>
      <div id="lyrics-variant-big-container">
      @foreach ($song->lyrics_variants as $var_no => $lyrics)
        <div class="variant-container">
          <x-input type="TEXT" name="lyrics[]" :var-no="$var_no" label="Tekst" value="{!! $lyrics !!}" rows="20" />
          <div class="flex right spread and-cover">
            <x-shipyard.ui.button id="remove-lyrics-variant"
              :var-no="$var_no"
              label="Usuń wariant"
              icon="delete"
              action="none"
              class="danger"
            />
          </div>
          <hr>
        </div>
        <script>
          document.querySelector("#remove-lyrics-variant[var-no='{{ $var_no }}']").addEventListener("click", (e) => { e.preventDefault(); e.target.closest(".variant-container").remove(); })
        </script>
      @endforeach
      </div>
      <div class="flex right spread and-cover">
        <x-shipyard.ui.button id="addLyricsVariantButton"
          label="Dodaj wariant"
          icon="plus"
          action="none"
          class="tertiary"
        />
      </div>
    </div>
    <div>
      <div id="variant-big-container">
      @foreach ($song->sheet_music_variants as $var_no => $notes)
        <div class="variant-container">
          <x-input type="TEXT" name="sheet_music[]" :var-no="$var_no" label="Nuty" value="{!! $notes !!}" rows="10" />
          <div id="note-transpose-{{ $var_no }}" class="flex right center wrap">
            <x-shipyard.ui.button
              icon="music-note-plus"
              pop="Transponuj w górę"
              action="none"
              class="tertiary"
              name="up"
            />
            <x-shipyard.ui.button
              icon="music-note-minus"
              pop="Transponuj w dół"
              action="none"
              class="tertiary"
              name="down"
            />
          </div>
          <div id="sheet-music-container-{{ $var_no }}"></div>
          <div class="flex right spread and-cover">
            <x-shipyard.ui.button id="remove-variant"
              :var-no="$var_no"
              label="Usuń wariant"
              icon="delete"
              action="none"
              class="danger"
            />
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
      <div class="flex right spread and-cover">
        <x-shipyard.ui.button id="addVariantButton"
          label="Dodaj wariant"
          icon="plus"
          action="none"
          class="tertiary"
        />
      </div>

      <p class="ghost">
        Zapis nutowy korzysta z formatu ABC. Dokumentacja jest <a href="https://abcnotation.com/wiki/abc:standard:v2.1" target="_blank">tutaj</a>.
      </p>
    </div>
  </div>
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
      :action="route('changes', ['type' => 'song', 'id' => $song->title])"
      target="_blank"
    />
    @endif
  </x-slot:actions>
</x-shipyard.app.form>

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
        jazzchords: true,
        germanAlphabet: true,
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
