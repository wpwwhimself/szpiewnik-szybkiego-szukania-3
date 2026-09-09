@extends("shipyard::layouts.admin")
@section("title", "Wyszukiwarka")
@section("subtitle", "Części stałe")

@section("prepends")
<script>
function enableNextContourBtns(radio) {
    const nextRadioContainer = radio.parentElement.nextElementSibling;
    nextRadioContainer?.querySelectorAll("input[type=radio]").forEach((el) => el.disabled = false);
}

function resetContour() {
    document.querySelectorAll("input[type=radio][name^='contour']").forEach((el) => {
        el.checked = false;
        el.disabled = el.name !== "contour[1]";
    });
}

function search() {
    const resultsContainer = document.getElementById("searchResults");

    const contourInputs = document.querySelectorAll("input[type=radio][name^='contour']");
    const contour = Array.from(contourInputs)
        .filter((el) => el.checked)
        .map((el) => el.value)
        .join("");

    if (contour.length < 2) {
        popToast("error", "Kontur jest zbyt krótki")
        return;
    }

    resultsContainer.classList.remove("hidden");
    resultsContainer.querySelector(".loader").classList.remove("hidden");

    fetchPublic("/api/ordinarium/search?" + new URLSearchParams({
        c: contour,
    }))
        .then(res => res.json())
        .then(({data, html}) => {
            resultsContainer.querySelector(".contents").innerHTML = html;
        })
        .catch((err) => {
            console.error(err);
            resultsContainer.querySelector(".contents").innerHTML = "<p class='accent error'>Wystąpił błąd podczas wyszukiwania.</p>";
        })
        .finally(() => {
            resultsContainer.querySelector(".loader").classList.add("hidden");
        });
}
</script>
@endsection

@section("content")

<x-shipyard::app.section
    title="Filtry"
    icon="filter"
    :extended="true"
>
    <x-shipyard::app.card>
        Wyszukiwarka części stałych szuka melodii na podstawie podanego jej konturu, tzn. czy linia melodyczna się wznosi czy opada z każdym kolejnym dźwiękiem.
    </x-shipyard::app.card>

    <div class="input-container flex right nowrap middle" style="justify-content: flex-start;">
        <label for="">
            <x-shipyard::app.icon-label-value icon="chart-line-variant">Kontur</x-shipyard::app.icon-label-value>
        </label>

        <div class="flex down middle nowrap">
            <span>↑</span>
            <span>✳️</span>
            <span>↓</span>
        </div>

        @for ($i = 1; $i < 10; $i++)
        <div class="flex down nowrap">
            <input type="radio" name="contour[{{ $i }}]" value="+" onchange="enableNextContourBtns(this)" @disabled($i > 1) />
            <input type="radio" name="contour[{{ $i }}]" value="0" onchange="enableNextContourBtns(this)" @disabled($i > 1) />
            <input type="radio" name="contour[{{ $i }}]" value="-" onchange="enableNextContourBtns(this)" @disabled($i > 1) />
        </div>
        @endfor

        <x-shipyard::ui.button
            icon="close"
            pop="Resetuj"
            action="none"
            onclick="resetContour()"
            class="tertiary"
        />
    </div>

    <div class="flex right center middle">
        <x-shipyard::ui.button
            icon="magnify"
            label="Szukaj"
            action="none"
            onclick="search()"
            class="primary"
        />
    </div>
</x-shipyard::app.section>

<x-shipyard::app.card id="searchResults" class="hidden">
</x-shipyard::app.card>

@endsection