<header class="topbar">
    <div class="brand">
        <div class="brand-mark">!</div>
        <div class="brand-copy">
            <small>Incident</small>
            <strong>Meldsysteem</strong>
        </div>
    </div>

    <nav class="nav">
        <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'is-active' : '' }}">Welkom</a>
        <a href="{{ route('incidents.index') }}" class="{{ request()->routeIs('incidents.index') ? 'is-active' : '' }}">Alle meldingen</a>
        @auth
        <a href="{{ route('incidents.create') }}" class="{{ request()->routeIs('incidents.create') ? 'is-active' : '' }}">Melding maken</a>
        @if (auth()->user()->isBeheerder())
        <a href="{{ route('beheer.index') }}" class="{{ request()->routeIs('beheer.*') ? 'is-active' : '' }}">Beheer</a>
        @endif
        @endauth
    </nav>

    <div class="actions">
        @auth
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-primary">Uitloggen</button>
        </form>
        @else
        <a class="btn btn-outline" href="{{ route('register') }}">Registreren</a>
        <a class="btn btn-primary" href="{{ route('login') }}">Inloggen</a>
        @endauth
    </div>
</header>