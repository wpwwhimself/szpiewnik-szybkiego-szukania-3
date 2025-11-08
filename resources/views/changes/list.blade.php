@extends("layouts.shipyard.admin")
@section("title" , $item->name ?? $item->title)
@section("subtitle", "Historia zmian")

@section("content")

<table>
    <thead>
        <tr>
            <th>Data</th>
            <th>Użytkownik</th>
            <th>Zmiana</th>
            <th>Szczegóły</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($item->changes as $change)
        <tr>
            <td title="{{ $change->date }}">{{ $change->date->diffForHumans() }}</td>
            <td>{{ $change->user->name }}</td>
            <td>{{ $change->action }}</td>
            <td>
                <ul>
                    @forelse ($change->details ?? [] as $field => $old_and_new)
                    <li>
                        <strong>{{ $field }}</strong>:
                        <div class="grid" style="--col-count: 2;">
                            @foreach ($old_and_new as $i => $value)
                                @if (is_array($value))
                                {{-- $value is a relationship - list all related --}}
                                <ol @if ($i == 0) class="ghost" @endif>
                                    @foreach ($value as $relationship_item)
                                    <li>
                                        <pre>@foreach ($relationship_item as $k => $v)
{{ $k }}: {{ $v }}
@endforeach</pre>
                                    </li>
                                    @endforeach
                                </ol>
                                @else
                                <pre @if ($i == 0) class="ghost" @endif>{{ $value }}</pre>
                                @endif
                            @endforeach
                        </div>
                    </li>
                    @empty
                    —
                    @endforelse
                </ul>
            </td>
        </tr>
        @empty
        <tr>

        </tr>
        @endforelse
    </tbody>
</table>

@endsection
