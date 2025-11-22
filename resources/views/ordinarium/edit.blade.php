@extends("layouts.shipyard.admin")
@section("title", $ordinarius ?? "Nowa część stała")
@section("subtitle", "Edycja części stałej")

@section("content")

<x-shipyard.app.form method="post" :action="route('ordinarius-edit')">
    <script src="{{ asset("js/note-transpose.js") }}"></script>

<x-shipyard.app.section title="Nuty" icon="music-note">
    <div id="variant-big-container">
    @foreach ($ordinarius->sheet_music_variants as $var_no => $notes)
        <div class="variant-container grid but-mobile-down" style="--col-count: 2;">
            <x-input type="TEXT" name="sheet_music[]" :var-no="$var_no" label="Nuty" value="{!! $notes !!}" rows="5" />
            <div>
                <div id="note-transpose-{{ $var_no }}" class="flex right center wrap">
                    <x-shipyard.ui.button
                        icon="music-note-plus"
                        pop="Transponuj w górę"
                        action="none"
                        class="tertiary"
                        name="up"
                        onclick="Hoch(this.closest(`.variant-container`).querySelector(`textarea[name^='sheet_music']`));"
                    />
                    <x-shipyard.ui.button
                        icon="music-note-minus"
                        pop="Transponuj w dół"
                        action="none"
                        class="tertiary"
                        name="down"
                        onclick="Runter(this.closest(`.variant-container`).querySelector(`textarea[name^='sheet_music']`));"
                    />
                </div>
                <div id="sheet-music-container-{{ $var_no }}"></div>
                <div class="flex right spread and-cover">
                    <x-shipyard.ui.button id="remove-variant" :var-no="$var_no"
                        label="Usuń wariant"
                        icon="delete"
                        action="none"
                        class="tertiary"
                        onclick="event.target.closest(`.variant-container`).remove();"
                    />
                </div>
            </div>
            <hr style="grid-column: span 2">
        </div>
    @endforeach
    </div>
    <div class="flex right spread and-cover">
        <x-shipyard.ui.button id="addVariantButton"
            label="Dodaj wariant"
            icon="plus"
            action="none"
            onclick="addVariant(event)"
            class="tertiary"
        />
    </div>
</x-shipyard.app.section>

    <input type="hidden" name="color_code" value="{{ $ordinarius->color_code }}" />
    <input type="hidden" name="part" value="{{ $ordinarius->part }}" />

    <x-slot:actions>
        <x-shipyard.ui.button
            label="Zatwierdź i wróć"
            class="primary"
            action="submit"
        />
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
    const newVariant = document.querySelector(".variant-container:first-of-type").cloneNode(true);
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
}
</script>

@endsection
