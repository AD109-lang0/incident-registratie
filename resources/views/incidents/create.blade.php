<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nieuwe melding | {{ config('app.name', 'IncidentDesk') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="incident-page">
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 24px 24px 72px;">
        <x-layout />
    </div>

    <div class="incident-shell">
        <div class="incident-header">
            <span class="eyebrow">Incident melden</span>
            <h1>Maak een nieuwe melding</h1>
            <p>Vul hieronder de details van het incident in zodat het team snel kan reageren.</p>
        </div>

        @if ($errors->any())
        <div class="auth-alert auth-alert-error">
            @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
            @endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('incidents.store') }}" class="incident-form">
            @csrf

            <div class="form-field">
                <label for="title">Titel</label>
                <input id="title" name="title" type="text" value="{{ old('title') }}" required>
            </div>

            <div class="form-field">
                <label for="description">Beschrijving</label>
                <textarea id="description" name="description" required>{{ old('description') }}</textarea>
            </div>

            <div class="incident-grid">
                <div class="form-field">
                    <label for="location">Locatie</label>
                    <select id="location" name="location" required>
                        <option value="">Kies een locatie</option>
                        @foreach ($locations as $location)
                        <option value="{{ $location->name }}" {{ old('location') === $location->name ? 'selected' : '' }}>{{ $location->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-field">
                    <label for="occurred_at">Datum & tijd</label>
                    <input id="occurred_at" name="occurred_at" type="datetime-local" value="{{ old('occurred_at') }}" required>
                </div>
            </div>

            <div class="form-field">
                <label for="type">Type incident</label>
                <select id="type" name="type" required>
                    <option value="">Kies een type</option>
                    @foreach ($incidentTypes as $incidentType)
                    <option value="{{ $incidentType->name }}" {{ old('type') === $incidentType->name ? 'selected' : '' }}>{{ $incidentType->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="incident-actions">
                <a class="btn btn-outline" href="{{ route('login') }}">Annuleren</a>
                <button type="submit" class="btn btn-primary auth-button">Incident melden</button>
            </div>
        </form>
    </div>
</body>

</html>