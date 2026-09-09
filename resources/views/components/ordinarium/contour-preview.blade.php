@props([
    "data"
])

@foreach ($data?->contour ?? [] as $cont)
<pre>{{ $cont }}</pre>
@endforeach
