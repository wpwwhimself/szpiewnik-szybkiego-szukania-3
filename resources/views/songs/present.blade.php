@extends("layout")

@section("content")

<div class="container" id="root"></div>
<script src="{{ asset('/js/song.js') }}?{{ time() }}"></script>
<script src="{{ asset("js/note-transpose.js") }}"></script>

@endsection
