<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inloggen | {{ config('app.name', 'IncidentDesk') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="auth-page">
    <x-layout />

    <div class="auth-shell">
        <div class="auth-panel auth-panel-visual">
            <div class="brand-mark">!</div>
            <span class="eyebrow">Incident Meldsysteem</span>
            <h1>Welkom terug.</h1>
            <p class="lead">Log in om meldingen te volgen, prioriteiten te beheren en incidenten snel op te lossen.</p>

            <ul class="auth-benefits">
                <li>Realtime incident overzicht</li>
                <li>Snelle vervolgacties en updates</li>
                <li>Veilig beheer voor teams</li>
            </ul>
        </div>

        <div class="auth-panel auth-panel-form">
            <div class="auth-heading">
                <p class="auth-kicker">Inloggen</p>
                <h2>Open je account</h2>
            </div>

            @if ($errors->any())
            <div class="auth-alert auth-alert-error">
                @foreach ($errors->all() as $error)
                <span>{{ $error }}</span>
                @endforeach
            </div>
            @endif

            @if (session('status'))
            <div class="auth-alert auth-alert-success">
                {{ session('status') }}
            </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" class="auth-form">
                @csrf

                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="form-group">
                    <label for="password">Wachtwoord</label>
                    <input id="password" name="password" type="password" required>
                </div>

                <div class="form-row">
                    <label class="remember-me">
                        <input type="checkbox" name="remember" value="1">
                        <span>Onthoud mij</span>
                    </label>

                    <a href="#">Wachtwoord vergeten?</a>
                </div>

                <button type="submit" class="btn btn-primary auth-button">Inloggen</button>
            </form>

            <p class="auth-footer">
                Nog geen account?
                <a href="{{ route('register') }}">Registreren</a>
            </p>
        </div>
    </div>
</body>

</html>