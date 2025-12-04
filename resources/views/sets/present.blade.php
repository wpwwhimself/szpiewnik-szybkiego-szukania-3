@extends("layouts.shipyard.admin")
@section("title", $set)
@section("subtitle", "Zestaw")

@section("prepends")
<script>
const user_id = {{ Auth::id() ?? "null" }};
</script>
@endsection

@section("content")

<div class="container" id="root"></div>
<script src="{{ asset('/js/react/set.js') }}?{{ time() }}"></script>
<script src="{{ asset("js/note-transpose.js") }}?{{ time() }}"></script>

<div class="flex right spread and-cover">
    @auth
    <x-shipyard.ui.button
        label="Edytuj mszę"
        icon="pencil"
        :action="route('set', ['set_id' => $set->id])"
    />

    @if (Auth::user()->hasRole("set-manager"))
    <x-shipyard.ui.button
        label="Utwórz i edytuj kopię"
        icon="content-copy"
        :action="route('set-copy-for-user', ['set' => $set])"
        class="danger"
    />
    @endif

    @endauth
    <x-shipyard.ui.button
        label="Na początek"
        icon="arrow-up"
        action="none"
        onclick="window.scrollTo({top: 0, behavior: 'smooth'});"
        class="tertiary"
    />
</div>

<script>
const set_notes_for_current_user = {!! json_encode($set->notesForCurrentUser->count() ? $set->notesForCurrentUser : []) !!};
</script>

@endsection
