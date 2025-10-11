@extends("layout")

@section("content")

<h1>Parametry części stałej</h1>

<form method="post" action="{{ route("ordinarius-edit") }}">
    @csrf
    <script src="{{ asset("js/note-transpose.js") }}"></script>

    <div id="variant-big-container">
    @foreach ($ordinarius->sheet_music_variants as $var_no => $notes)
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
        document.querySelector("#note-transpose-{{ $var_no }} button[name=up]").addEventListener("click", (e) => { e.preventDefault(); Hoch(document.querySelector("textarea[var-no='{{ $var_no }}']"), e); });
        document.querySelector("#note-transpose-{{ $var_no }} button[name=down]").addEventListener("click", (e) => { e.preventDefault(); Runter(document.querySelector("textarea[var-no='{{ $var_no }}']"), e); });
        document.querySelector("#remove-variant[var-no='{{ $var_no }}']").addEventListener("click", (e) => { e.preventDefault(); e.target.closest(".variant-container").remove(); })
        </script>
    @endforeach
    </div>

    <input type="hidden" name="color_code" value="{{ $ordinarius->color_code }}" />
    <input type="hidden" name="part" value="{{ $ordinarius->part }}" />

    <div class="flex-right stretch">
        <x-button id="addVariantButton">Dodaj wariant</x-button>
        <x-button type="submit">Zatwierdź i wróć</x-button>
    </div>
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
      jazzchords: true,
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
    newVariant.querySelector(`#note-transpose-${new_var_no} button[name=up]`).addEventListener("click", (e) => { e.preventDefault(); Hoch(document.querySelector(`textarea[var-no='${new_var_no}']`), e); });
    newVariant.querySelector(`#note-transpose-${new_var_no} button[name=down]`).addEventListener("click", (e) => { e.preventDefault(); Runter(document.querySelector(`textarea[var-no='${new_var_no}']`), e); });
    newVariant.querySelector(`#remove-variant`).addEventListener("click", (e) => { e.preventDefault(); e.target.closest(".variant-container").remove(); })
}
document.getElementById("addVariantButton").addEventListener("click", addVariant);
</script>

@endsection
