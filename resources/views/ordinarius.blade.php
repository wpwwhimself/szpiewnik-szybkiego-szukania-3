@extends("layout")

@section("content")

<h1>Parametry części stałej</h1>
<form method="post" action="{{ route("ordinarius-edit") }}">
    <div>
        <x-input type="TEXT" name="sheet_music" label="Nuty" value="{{ $ordinarius->sheet_music }}" />
    </div>
    <SheetMusicRender notes={ordinarius.sheetMusic} />
    <div class="flex-right stretch">
        <x-button type="submit">Zatwierdź i wróć</x-button>
    </div>
</form>

@endsection
