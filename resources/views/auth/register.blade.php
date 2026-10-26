<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registreren</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-100 min-h-screen flex items-center justify-center p-6 text-black">
    <x-layout />

    <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8">
        <div class="mb-6 text-center">
            <h1 class="text-2xl font-bold text-black">Maak een account</h1>
            <p class="mt-2 text-sm text-black">Vul hieronder je gegevens in</p>
        </div>

        @if (session('status'))
        <div class="mb-4 rounded-md bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">
            {{ session('status') }}
        </div>
        @endif

        @if ($errors->any())
        <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <ul class="list-disc space-y-1 pl-5">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('register.post') }}" class="space-y-5" novalidate>
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-black mb-1">Naam</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required
                    class="w-full rounded-lg border text-black {{ $errors->has('name') ? 'border-red-300 focus:border-red-500 focus:ring-red-100' : 'border-slate-300 focus:border-blue-500 focus:ring-blue-100' }} px-3 py-2.5 focus:outline-none focus:ring-2"
                    placeholder="Jan Jansen">
                @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-black mb-1">E-mail</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required
                    class="w-full rounded-lg border text-black {{ $errors->has('email') ? 'border-red-300 focus:border-red-500 focus:ring-red-100' : 'border-slate-300 focus:border-blue-500 focus:ring-blue-100' }} px-3 py-2.5 focus:outline-none focus:ring-2"
                    placeholder="naam@email.com">
                @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-black mb-1">Wachtwoord</label>
                <input id="password" name="password" type="password" required
                    class="w-full rounded-lg border text-black {{ $errors->has('password') ? 'border-red-300 focus:border-red-500 focus:ring-red-100' : 'border-slate-300 focus:border-blue-500 focus:ring-blue-100' }} px-3 py-2.5 focus:outline-none focus:ring-2"
                    placeholder="••••••••">
                @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-black mb-1">Bevestig wachtwoord</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required
                    class="w-full rounded-lg border text-black {{ $errors->has('password') ? 'border-red-300 focus:border-red-500 focus:ring-red-100' : 'border-slate-300 focus:border-blue-500 focus:ring-blue-100' }} px-3 py-2.5 focus:outline-none focus:ring-2"
                    placeholder="••••••••">
            </div>

            <button type="submit"
                class="w-full rounded-lg bg-blue-600 px-4 py-3 font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-200">
                Registreren
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-black">
            Heb je al een account?
            <a href="{{ url('/login') }}" class="font-medium text-blue-600 hover:text-blue-500">Inloggen</a>
        </p>
    </div>
</body>

</html>