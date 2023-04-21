@extends("layout")

@section("content")
<h1>Załaduj mszę</h1>
<div>
@foreach ($formulas as $formula)
    <h2>{{ $formula }}</h2>
    <div class="flex-right center wrap">
    @foreach ($sets[$formula] as $set)
        <a href="{{ route('set-show', ['name' => Str::slug($set->name)]) }}">{{ $set->name }}</a>
    @endforeach
    </div>
@endforeach
</div>
@endsection
