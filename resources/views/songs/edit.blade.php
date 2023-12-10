@extends("layout")

@section("content")

<h1>Parametry pieśni</h1>
<form method="post" action="{{ route('song-edit') }}">
  @csrf
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
    <x-input type="TEXT" name="lyrics" label="Tekst" value="{!! $song->lyrics !!}" />
    <div>
      <x-input type="TEXT" name="sheet_music" label="Nuty" value="{!! $song->sheet_music !!}" />
      <div id="note-transpose" class="flex-right center wrap">
        <x-button name="up">♪+</x-button>
        <x-button name="down">♪-</x-button>
        <script src="{{ asset("js/note-transpose.js") }}"></script>
        <script>
        document.querySelector("#note-transpose button[name=up]").addEventListener("click", (e) => Hoch(document.getElementById("sheet_music"), e));
        document.querySelector("#note-transpose button[name=down]").addEventListener("click", (e) => Runter(document.getElementById("sheet_music"), e));
        </script>
      </div>
    </div>
  </div>

  <div id="sheet-music-container"></div>

  @if (Auth::user()?->clearance->id >= 2)
  <div class="flex-right stretch">
    <x-button type="submit" name="action" value="update">Zatwierdź i wróć</x-button>
    <x-button type="submit" name="action" value="delete">Usuń</x-button>
  </div>
  @endif
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
      germanAlphabet: true,
    }
  );
}
render();
document.getElementById("sheet_music").addEventListener("keyup", () => render());
</script>

@endsection
