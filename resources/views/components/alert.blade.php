@props(['status'])

<div class="flex-right stretch {{ $status }}">
    <h2>{{ session($status) }}</h2>
</div>
