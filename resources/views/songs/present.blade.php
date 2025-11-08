@extends("layouts.shipyard.admin")
@section("title", $song)
@section("subtitle", "Pieśń")

@section("content")

<div class="container" id="root"></div>
<script src="{{ asset('/js/react/song.js') }}?{{ time() }}"></script>
<script src="{{ asset("js/note-transpose.js") }}?{{ time() }}"></script>

@endsection
