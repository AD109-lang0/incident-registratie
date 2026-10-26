<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Incident bewerken | {{ config('app.name', 'IncidentDesk') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="incident-page">
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 24px 24px 72px;">
        <x-layout />
    </div>

    <div class="incident-shell">
        <div class="incident-header">
            <span class="eyebrow">Beheer</span>
            <h1>Incident bewerken</h1>
            <p>Pas de gegevens en status van dit incident aan.</p>
        </div>

        <form method="POST" action="{{ route('beheer.incidents.update', $incident) }}" class="incident-form">
            @csrf
            @method('PATCH')

            <label for="title">Titel</label>
            <input id="title" name="title" type="text" value="{{ old('title', $incident->title) }}" required>

            <label for="description">Omschrijving</label>
            <textarea id="description" name="description" required>{{ old('description', $incident->description) }}</textarea>

            <div class="incident-grid">
                <div>
                    <label for="location">Locatie</label>
                    <input id="location" name="location" type="text" value="{{ old('location', $incident->location) }}" required>
                </div>
                <div>
                    <label for="occurred_at">Datum en tijd</label>
                    <input id="occurred_at" name="occurred_at" type="datetime-local" value="{{ old('occurred_at', $incident->occurred_at->format('Y-m-d\\TH:i')) }}" required>
                </div>
                <div>
                    <label for="type">Type</label>
                    <input id="type" name="type" type="text" value="{{ old('type', $incident->type) }}" required>
                </div>
                <div>
                    <label for="status">Status</label>
                    <select id="status" name="status" required>
                        @foreach (['Open', 'In behandeling', 'Opgelost'] as $status)
                        <option value="{{ $status }}" @selected(old('status', $incident->status) === $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="assigned_to_user_id">Verantwoordelijke</label>
                    <select id="assigned_to_user_id" name="assigned_to_user_id">
                        <option value="">Nog niet toegewezen</option>
                        @foreach ($responsibles as $responsible)
                        <option value="{{ $responsible->id }}" @selected((string) old('assigned_to_user_id', $incident->assigned_to_user_id) === (string) $responsible->id)>{{ $responsible->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <label for="notes">Notities</label>
            <textarea id="notes" name="notes">{{ old('notes', $incident->notes) }}</textarea>

            <div class="incident-actions">
                <button type="submit" class="btn btn-primary">Opslaan</button>
                <a href="{{ route('incidents.index') }}" class="btn btn-outline">Annuleren</a>
            </div>
        </form>
    </div>
</body>

</html>