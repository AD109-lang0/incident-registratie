<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Incidenttypes | {{ config('app.name', 'IncidentDesk') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="incident-page">
    <div class="container">
        <x-layout />
    </div>

    <main class="mx-auto w-full max-w-6xl px-5 pb-12 sm:px-8">
        <section class="relative isolate overflow-hidden rounded-[2rem] border border-slate-700/70 bg-gradient-to-br from-slate-900 via-slate-900 to-rose-950/50 p-7 shadow-2xl shadow-slate-950/40 sm:p-10">
            <div aria-hidden="true" class="absolute -right-20 -top-24 -z-10 size-72 rounded-full bg-rose-500/10 blur-3xl"></div>
            <div aria-hidden="true" class="absolute -bottom-32 left-1/3 -z-10 size-64 rounded-full bg-blue-500/10 blur-3xl"></div>

            <div class="flex flex-col gap-8 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <span class="eyebrow">Incidentcatalogus</span>
                    <h1 class="mt-5 max-w-3xl text-white">Types voor elke melding</h1>
                    <p class="mt-5 max-w-2xl text-base leading-7 text-slate-300 sm:text-lg">
                        Bekijk de categorieën die je kunt gebruiken om incidenten snel en overzichtelijk te melden.
                    </p>
                </div>

                @auth
                <a class="btn btn-primary shrink-0" href="{{ route('incidents.create') }}">Melding maken</a>
                @else
                <a class="btn btn-primary shrink-0" href="{{ route('register') }}">Aan de slag</a>
                @endauth
            </div>

            <div class="mt-9 grid gap-4 border-t border-slate-700/70 pt-6 sm:grid-cols-2">
                <div class="flex items-center gap-3">
                    <span class="flex size-11 items-center justify-center rounded-2xl border border-rose-300/20 bg-rose-400/10 text-lg font-bold text-rose-200">
                        {{ $incidentTypes->count() }}
                    </span>
                    <div>
                        <p class="text-sm font-semibold text-white">Actieve types</p>
                        <p class="text-sm text-slate-400">Klaar om te gebruiken</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="flex size-11 items-center justify-center rounded-2xl border border-sky-300/20 bg-sky-400/10 text-lg font-bold text-sky-200">
                        {{ $incidentCount }}
                    </span>
                    <div>
                        <p class="text-sm font-semibold text-white">Geregistreerde meldingen</p>
                        <p class="text-sm text-slate-400">Over alle categorieën</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="mt-10" aria-labelledby="types-heading">
            <div class="mb-5 flex flex-wrap items-end justify-between gap-3">
                <div>
                    <p class="eyebrow">Kies een categorie</p>
                    <h2 id="types-heading" class="mt-4 text-2xl font-bold tracking-tight text-white sm:text-3xl">Alle incidenttypes</h2>
                </div>
                <p class="text-sm text-slate-400">{{ $incidentTypes->count() }} {{ $incidentTypes->count() === 1 ? 'type' : 'types' }}</p>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                @forelse ($incidentTypes as $incidentType)
                <article class="group flex flex-col rounded-3xl border border-slate-700/70 bg-slate-900/70 p-6 transition duration-300 hover:-translate-y-1 hover:border-rose-300/40 hover:bg-slate-900 hover:shadow-xl hover:shadow-rose-950/20">
                    <div class="flex items-start justify-between gap-4">
                        <span class="flex size-14 items-center justify-center rounded-2xl border border-rose-300/20 bg-gradient-to-br from-rose-400/20 to-orange-400/10 text-rose-200">
                            <svg aria-hidden="true" class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3.75 20.25 8.5v7L12 20.25 3.75 15.5v-7L12 3.75Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="m3.9 8.6 8.1 4.7 8.1-4.7M12 13.3v6.8" />
                            </svg>
                        </span>
                        <span class="rounded-full border border-emerald-300/20 bg-emerald-400/10 px-3 py-1 text-xs font-semibold text-emerald-200">Actief</span>
                    </div>

                    <h3 class="mt-6 text-xl font-semibold text-white">{{ $incidentType->name }}</h3>
                    <p class="mt-2 flex-1 text-sm leading-6 text-slate-400">
                        Gebruik dit type om een melding bij de juiste categorie onder te brengen.
                    </p>

                    <div class="mt-6 flex items-center justify-between border-t border-slate-700/70 pt-4">
                        <span class="text-sm text-slate-400">{{ $incidentCounts->get($incidentType->name, 0) }} {{ $incidentCounts->get($incidentType->name, 0) === 1 ? 'melding' : 'meldingen' }}</span>
                        <a class="inline-flex items-center gap-2 text-sm font-semibold text-rose-200 transition group-hover:text-white"
                            href="{{ route('incidents.index', ['type' => $incidentType->name]) }}">
                            Bekijk meldingen
                            <svg aria-hidden="true" class="size-4 transition group-hover:translate-x-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h10.69L10.72 5.53a.75.75 0 1 1 1.06-1.06l5 5a.75.75 0 0 1 0 1.06l-5 5a.75.75 0 1 1-1.06-1.06l3.72-3.72H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </article>
                @empty
                <div class="rounded-3xl border border-dashed border-slate-700 bg-slate-900/50 px-6 py-14 text-center sm:col-span-2 xl:col-span-3">
                <span class="mx-auto flex size-14 items-center justify-center rounded-2xl bg-slate-800 text-slate-300">
                    <svg aria-hidden="true" class="size-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M4.93 19h14.14c1.54 0 2.5-1.67 1.73-3L13.73 3.8a2 2 0 0 0-3.46 0L3.2 16c-.77 1.33.19 3 1.73 3Z" />
                    </svg>
                </span>
                <h3 class="mt-5 text-lg font-semibold text-white">Nog geen actieve types</h3>
                <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-400">
                    Er zijn op dit moment geen categorieën beschikbaar. Neem contact op met een beheerder om types toe te voegen.
                </p>
                </div>
                @endforelse
            </div>
        </section>

        <section class="mt-10 flex flex-col gap-4 rounded-3xl border border-rose-300/15 bg-gradient-to-r from-rose-500/10 via-slate-900/80 to-slate-900/80 p-6 sm:flex-row sm:items-center sm:justify-between sm:p-8">
            <div>
                <h2 class="text-xl font-semibold text-white">Klaar om iets te melden?</h2>
                <p class="mt-2 text-sm leading-6 text-slate-400">Kies een type en help ons je melding sneller op de juiste plek te krijgen.</p>
            </div>
            @auth
            <a class="btn btn-primary shrink-0" href="{{ route('incidents.create') }}">Start een melding</a>
            @else
            <a class="btn btn-primary shrink-0" href="{{ route('register') }}">Account aanmaken</a>
            @endauth
        </section>
    </main>
</body>

</html>