<div role="brand" class="flex right center middle">
    <x-shipyard::app.logo />
    <div class="flex down">
        <h1 role="title">{{ setting("app_name") }}</h1>
        <h2 role="subtitle">{{ $slot }}</h2>
    </div>
</div>
