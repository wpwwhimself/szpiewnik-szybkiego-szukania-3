@extends("layouts.shipyard.admin")
@section("title", "Formuły")

@section('content')

<p>
    Formuły dotyczą zmian w porządku mszy przy okazji pewnych okresów, świąt lub uroczystości.
</p>

@if (Auth::user()?->hasRole("formula-manager"))
<x-shipyard.ui.button
    :action="route('formula-add')"
    label="Dodaj nową"
    icon="plus"
/>
@endif

<div class="flex right center wrap">
@foreach ($formulas as $formula)
    <a href="{{ route('formula', ['name_slug' => Str::slug($formula->name)]) }}">{{ $formula->name }}</a>
@endforeach
</div>

@endsection
