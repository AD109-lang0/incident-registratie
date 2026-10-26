<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Incidenten | {{ config('app.name', 'IncidentDesk') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="incident-page">
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 24px 24px 72px;">
        <x-layout />
    </div>

    <div class="incident-shell">
        <div class="incident-header">
            <span class="eyebrow">Incidentoverzicht</span>
            <h1>Alle incidenten</h1>
            <p>Hier vind je alle gemelde incidenten en hun huidige status.</p>
        </div>

        <form method="GET" action="{{ route('incidents.index') }}" class="incident-form">
            <div class="incident-grid">
                <div>
                    <label for="type">Type</label>
                    <select id="type" name="type">
                        <option value="">Alle types</option>
                        @foreach ($incidentTypes as $incidentType)
                        <option value="{{ $incidentType->name }}" @selected(($filters['type'] ?? '' )===$incidentType->name)>{{ $incidentType->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="">Alle statussen</option>
                        @foreach ($statuses as $status)
                        <option value="{{ $status }}" @selected(($filters['status'] ?? '' )===$status)>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="location">Locatie</label>
                    <select id="location" name="location">
                        <option value="">Alle locaties</option>
                        @foreach ($locations as $location)
                        <option value="{{ $location->name }}" @selected(($filters['location'] ?? '' )===$location->name)>{{ $location->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="date_from">Vanaf datum</label>
                    <input id="date_from" name="date_from" type="date" value="{{ $filters['date_from'] ?? '' }}">
                </div>
                <div>
                    <label for="date_to">Tot en met datum</label>
                    <input id="date_to" name="date_to" type="date" value="{{ $filters['date_to'] ?? '' }}">
                </div>
            </div>
            <div class="incident-actions">
                <button type="submit" class="btn btn-primary">Filteren</button>
                <a href="{{ route('incidents.index') }}" class="btn btn-outline">Filters wissen</a>
            </div>
        </form>

        @if ($incidents->isEmpty())
        <div class="auth-alert auth-alert-success">
            Er zijn nog geen incidenten gemeld.
        </div>
        @else
        <div class="incident-list-panel">
            @foreach ($incidents as $incident)
            <article class="incident-card">
                <div class="incident-card-header">
                    <div>
                        <h2>{{ $incident->title }}</h2>
                        <p>{{ $incident->location }} • {{ $incident->occurred_at->format('d-m-Y H:i') }}</p>
                    </div>
                    <span class="status-pill status-{{ strtolower(str_replace(' ', '-', $incident->status)) }}">{{ $incident->status }}</span>
                </div>

                <p class="incident-description">{{ $incident->description }}</p>

                <div class="incident-meta">
                    <span><strong>Melder:</strong> {{ $incident->user->name }}</span>
                    <span><strong>Type:</strong> {{ $incident->type }}</span>
                    <span><strong>Status:</strong> {{ $incident->status }}</span>
                    <span><strong>Verantwoordelijke:</strong> {{ $incident->assignedTo?->name ?? 'Nog niet toegewezen' }}</span>
                </div>

                @if ($incident->notes)
                <p class="incident-description"><strong>Notities:</strong> {{ $incident->notes }}</p>
                @endif

                @if (auth()->user()->isCoordinator() || auth()->user()->isBeheerder())
                <div class="incident-actions">
                    <a class="btn btn-outline" href="{{ route('beheer.incidents.edit', $incident) }}">Bewerken</a>
                    <form method="POST" action="{{ route('beheer.incidents.destroy', $incident) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline">Verwijderen</button>
                    </form>
                </div>
                @endif
            </article>
            @endforeach
        </div>
        @endif

        <div class="incident-actions">
            <a class="btn btn-primary" href="{{ route('incidents.create') }}">Nieuwe melding</a>
            <a class="btn btn-outline" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Uitloggen</a>
        </div>

        <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display:none;">
            @csrf
        </form>
    </div>
</body>

</html>