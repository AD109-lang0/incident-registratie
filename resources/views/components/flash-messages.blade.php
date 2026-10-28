<div class="flash-messages" aria-live="polite">
    @if (session('status') || session('success'))
    <div class="auth-alert auth-alert-success" role="status">
        {{ session('status') ?? session('success') }}
    </div>
    @endif

    @if (session('error'))
    <div class="auth-alert auth-alert-error" role="alert">
        {{ session('error') }}
    </div>
    @endif

    @if ($errors->any())
    <div class="auth-alert auth-alert-error" role="alert">
        <p class="mb-2 font-semibold">De actie is niet gelukt. Controleer de volgende punten:</p>
        <ul class="list-disc space-y-1 pl-5">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
</div>
