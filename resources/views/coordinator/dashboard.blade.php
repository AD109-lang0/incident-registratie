<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Coördinator dashboard | {{ config('app.name', 'IncidentDesk') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="incident-page">
    <div class="container">
        <x-layout />
    </div>

    <main class="coordinator-dashboard mx-auto w-full max-w-6xl px-5 pb-12 sm:px-8">
        <div class="coordinator-dashboard-heading">
            <span class="eyebrow">Inzicht in meldingen</span>
            <h1>Coördinator dashboard</h1>
            <p>Bekijk waar en wanneer incidenten worden gemeld.</p>
        </div>

        <div class="chart-grid">
            <article class="chart-card" data-chart="types">
                <h2>Meldingen per type</h2>
                @forelse ($incidentTypeCounts as $incidentTypeCount)
                <div class="chart-row">
                    <span class="chart-label">{{ $incidentTypeCount->type }}: {{ $incidentTypeCount->total }}</span>
                    <span class="chart-track" role="img" aria-label="{{ $incidentTypeCount->type }}: {{ $incidentTypeCount->total }} {{ $incidentTypeCount->total === 1 ? 'incident' : 'incidenten' }}">
                        <span class="chart-bar chart-bar-rose" style="width: {{ ($incidentTypeCount->total / max($incidentTypeCounts->max('total'), 1)) * 100 }}%"></span>
                    </span>
                </div>
                @empty
                <p class="chart-empty">Geen meldingen beschikbaar</p>
                @endforelse
            </article>

            <article class="chart-card" data-chart="months">
                <h2>Meldingen per maand</h2>
                @forelse ($monthlyIncidentCounts as $month => $total)
                <div class="chart-row">
                    <span class="chart-label">{{ $month }}: {{ $total }}</span>
                    <span class="chart-track" role="img" aria-label="{{ $month }}: {{ $total }} {{ $total === 1 ? 'incident' : 'incidenten' }}">
                        <span class="chart-bar chart-bar-blue" style="width: {{ ($total / max($monthlyIncidentCounts->max(), 1)) * 100 }}%"></span>
                    </span>
                </div>
                @empty
                <p class="chart-empty">Geen meldingen beschikbaar</p>
                @endforelse
            </article>

            <article class="chart-card" data-chart="locations">
                <h2>Meldingen per locatie</h2>
                @forelse ($locationCounts as $locationCount)
                <div class="chart-row">
                    <span class="chart-label">{{ $locationCount->location }}: {{ $locationCount->total }}</span>
                    <span class="chart-track" role="img" aria-label="{{ $locationCount->location }}: {{ $locationCount->total }} {{ $locationCount->total === 1 ? 'incident' : 'incidenten' }}">
                        <span class="chart-bar chart-bar-amber" style="width: {{ ($locationCount->total / max($locationCounts->max('total'), 1)) * 100 }}%"></span>
                    </span>
                </div>
                @empty
                <p class="chart-empty">Geen meldingen beschikbaar</p>
                @endforelse
            </article>
        </div>
    </main>
</body>

</html>
