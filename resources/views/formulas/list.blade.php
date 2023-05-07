@extends("layout")

@section('content')

@if (Auth::user()?->clearance->id >= 3)
<a href="{{ route('formula-add') }}" class="flex-right stretch">
    <x-button>Dodaj nową</x-button>
</a>
@endif

<div class="flex-right center wrap">
@foreach ($formulas as $formula)
    <a href="{{ route('formula', ['name_slug' => Str::slug($formula->name)]) }}">{{ $formula->name }}</a>
@endforeach
</div>

@endsection
