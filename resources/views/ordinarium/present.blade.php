@extends("layouts.shipyard.admin")
@section("title", $color->display_name)
@section("subtitle", "Części stałe")

@section("content")

<div>
    <p>{{ ucfirst($color->desc) }}</p>
</div>
<div class="container" id="root"></div>
<script src="{{ asset('/js/react/ordinarius.js') }}?{{ time() }}"></script>

@endsection
