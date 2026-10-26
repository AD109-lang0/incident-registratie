<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Beheer | {{ config('app.name', 'IncidentDesk') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="incident-page">
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 24px 24px 72px;">
        <x-layout />
    </div>

    <main class="incident-shell">
        <div class="incident-header">
            <span class="eyebrow">Beheer</span>
            <h1>Applicatie beheren</h1>
            <p>Beheer incidenttypes, locaties en gebruikersrollen.</p>
        </div>

        @if (session('status'))
        <div class="auth-alert auth-alert-success">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
        <div class="auth-alert auth-alert-error">
            @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
            @endforeach
        </div>
        @endif

        <section class="incident-list-panel">
            <h2>Incidenttypes</h2>
            <form method="POST" action="{{ route('beheer.incidenttypes.store') }}" class="incident-form">
                @csrf
                <div class="form-field">
                    <label for="new-type">Nieuw incidenttype</label>
                    <input id="new-type" name="name" type="text" required>
                </div>
                <button type="submit" class="btn btn-primary">Toevoegen</button>
            </form>
            @foreach ($incidentTypes as $incidentType)
            <div class="incident-actions">
                <form method="POST" action="{{ route('beheer.incidenttypes.update', $incidentType) }}">
                    @csrf
                    @method('PATCH')
                    <input name="name" type="text" value="{{ $incidentType->name }}" required>
                    <button type="submit" class="btn btn-outline">Opslaan</button>
                </form>
                <form method="POST" action="{{ route('beheer.incidenttypes.destroy', $incidentType) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline">Verwijderen</button>
                </form>
            </div>
            @endforeach
        </section>

        <section class="incident-list-panel">
            <h2>Locaties</h2>
            <form method="POST" action="{{ route('beheer.locations.store') }}" class="incident-form">
                @csrf
                <div class="form-field">
                    <label for="new-location">Nieuwe locatie</label>
                    <input id="new-location" name="name" type="text" required>
                </div>
                <button type="submit" class="btn btn-primary">Toevoegen</button>
            </form>
            @foreach ($locations as $location)
            <div class="incident-actions">
                <form method="POST" action="{{ route('beheer.locations.update', $location) }}">
                    @csrf
                    @method('PATCH')
                    <input name="name" type="text" value="{{ $location->name }}" required>
                    <button type="submit" class="btn btn-outline">Opslaan</button>
                </form>
                <form method="POST" action="{{ route('beheer.locations.destroy', $location) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline">Verwijderen</button>
                </form>
            </div>
            @endforeach
        </section>

        <section class="incident-list-panel">
            <h2>Gebruikers en rollen</h2>
            @foreach ($users as $user)
            <form method="POST" action="{{ route('beheer.users.role', $user) }}" class="incident-actions">
                @csrf
                @method('PATCH')
                <span>{{ $user->name }} ({{ $user->email }})</span>
                <select name="role" required>
                    @foreach ($roles as $role)
                    <option value="{{ $role->value }}" @selected($user->role === $role)>{{ ucfirst($role->value) }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-outline">Rol opslaan</button>
            </form>
            @endforeach
        </section>
    </main>
</body>

</html>