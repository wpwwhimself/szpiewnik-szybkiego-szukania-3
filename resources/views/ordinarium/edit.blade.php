@extends("layout")

@section("content")

<h1>Parametry części stałej</h1>
<form method="post" action="{{ route("ordinarius-edit") }}">
    @csrf
    <div>
        <x-input type="TEXT" name="sheet_music" label="Nuty" value="{!! $ordinarius->sheet_music !!}" />
    </div>
    <div id="sheet-music-container"></div>
    <div class="flex-right stretch">
        <x-button type="submit">Zatwierdź i wróć</x-button>
    </div>
    <input type="hidden" name="color_code" value="{{ $ordinarius->color_code }}" />
    <input type="hidden" name="part" value="{{ $ordinarius->part }}" />
</form>

<script src="{{ asset("js/abcjs-basic-min.js") }}"></script>
<script defer>
function render(){
  const sheet = document.getElementById("sheet_music").value;
  ABCJS.renderAbc(
    "sheet-music-container",
    sheet,
    {
      responsive: "resize",
    //   germanAlphabet: true,
    }
  );
}
render();
document.getElementById("sheet_music").addEventListener("keyup", () => render());
</script>

@endsection
