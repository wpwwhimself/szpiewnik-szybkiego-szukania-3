@extends("layout")

@section("content")

<div class="container" id="root"></div>
<script src="{{ asset('/js/song.js') }}?{{ time() }}"></script>

@endsection
