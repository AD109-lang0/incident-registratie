<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registreren | {{ config('app.name', 'IncidentDesk') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="auth-page">
    <x-layout />

    <main class="w-full max-w-md rounded-3xl border border-slate-700/70 bg-slate-900/90 p-8 text-slate-100 shadow-2xl shadow-slate-950/50 backdrop-blur sm:p-10">
        <div class="mb-8 text-center">
            <div class="brand-mark mx-auto mb-5">!</div>
            <p class="auth-kicker">Incident Meldsysteem</p>
            <h1 class="mt-2 text-3xl font-bold tracking-tight text-white">Maak een account</h1>
            <p class="mt-2 text-sm text-slate-400">Vul hieronder je gegevens in</p>
        </div>

        <form method="POST" action="{{ route('register.post') }}" class="grid gap-5" novalidate>
            @csrf

            <div class="form-group">
                <label for="name">Naam</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required
                    class="placeholder:text-slate-500 {{ $errors->has('name') ? 'border-red-400 focus:border-red-400 focus:ring-red-400/20' : '' }}"
                    placeholder="Jan Jansen">
                @error('name')
                <p class="text-sm text-rose-300">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">E-mail</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required
                    class="placeholder:text-slate-500 {{ $errors->has('email') ? 'border-red-400 focus:border-red-400 focus:ring-red-400/20' : '' }}"
                    placeholder="naam@email.com">
                @error('email')
                <p class="text-sm text-rose-300">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Wachtwoord</label>
                <input id="password" name="password" type="password" required
                    class="placeholder:text-slate-500 {{ $errors->has('password') ? 'border-red-400 focus:border-red-400 focus:ring-red-400/20' : '' }}"
                    placeholder="••••••••">
                @error('password')
                <p class="text-sm text-rose-300">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Bevestig wachtwoord</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required
                    class="placeholder:text-slate-500 {{ $errors->has('password') ? 'border-red-400 focus:border-red-400 focus:ring-red-400/20' : '' }}"
                    placeholder="••••••••">
            </div>

            <button type="submit"
                class="btn btn-primary mt-2 w-full cursor-pointer border-0">
                Registreren
            </button>
        </form>

        <p class="mt-7 text-center text-sm text-slate-400">
            Heb je al een account?
            <a href="{{ route('login') }}" class="font-semibold text-rose-200 transition hover:text-white">Inloggen</a>
        </p>
    </main>
</body>

</html>