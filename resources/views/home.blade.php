@extends("shipyard::layouts.clean")

@section("content")

<x-brand>
    Ty graj, ja wskażę co i kiedy
</x-brand>

<p>
    Zadaniem tego śpiewnika jest wyświetlać wszystko, co będzie Ci potrzebne podczas mszy:
    pieśni, części stałe, wszystko w ładnym i zgrabnym porządku.
</p>

<div class="grid but-mobile-down" style="--col-count: 3;">
    <x-shipyard::app.card
        title="Pieśni i utwory religijne"
        :icon="model_icon('songs')"
    >
        <p>
            Bogata i stale rozwijana baza <strong>różnych pieśni, piosenek i innych utworów religijnych</strong>,
            pogrupowanych pod kątem ich przydatności w poszczególnych sytuacjach.
        </p>

        <div class="flex right center middle">
            <x-shipyard::ui.button
                icon="arrow-right"
                label="Przeglądaj"
                :action="route('songs')"
                class="primary"
            />
        </div>
    </x-shipyard::app.card>

    <x-shipyard::app.card
        title="Części stałe i psalmy"
        :icon="model_icon('ordinariuses')"
    >
        <p>
            Katalog melodii <strong>części stałych oraz psalmów</strong> pochodzących od różnych kompozytorów,
            połączonych w spójne msze dla zapewnienia odpowiedniego nastroju uroczystości.
        </p>

        <div class="flex right center middle">
            <x-shipyard::ui.button
                icon="arrow-right"
                label="Przeglądaj"
                :action="route('ordinarium')"
                class="primary"
            />
        </div>
    </x-shipyard::app.card>

    <x-shipyard::app.card
        title="Porządki mszy"
        :icon="model_icon('sets')"
    >
        <p>
            Gotowe <strong>zestawy pieśni oraz melodii części stałych</strong> na każdą mszę,
            pozwalające szybko przygotować się na daną okazję.
        </p>

        <div class="flex right center middle">
            <x-shipyard::ui.button
                icon="arrow-right"
                label="Przeglądaj"
                :action="route('sets')"
                class="primary"
            />
        </div>
    </x-shipyard::app.card>
</div>

@guest
<x-shipyard::app.section
    title="Masz konto?"
    :icon="model_icon('users')"
>
    <p>Za pomocą konta możesz tworzyć własne zestawy i dodawać nowe pieśni oraz części stałe.</p>
    <p>Przejdź do logowania/rejestracji przyciskiem na dole strony.</p>
</x-shipyard::app.section>
@endguest

@endsection
