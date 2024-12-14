@extends("layout")

@section("content")

<div class="flex-right center">
    <p>Zestaw {{ lcfirst($color->display_name) }}: {{ $color->desc }}</p>
</div>
<div class="container" id="root"></div>
<script src="{{ asset('/js/react/ordinarius.js') }}?{{ time() }}"></script>

@endsection
