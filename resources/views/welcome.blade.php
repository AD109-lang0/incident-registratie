<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'IncidentDesk') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div class="container">
        <x-layout />

        <main>
            @if (session('status'))
            <div class="auth-alert auth-alert-success" style="margin-top: 20px;">
                {{ session('status') }}
            </div>
            @endif

            <section class="hero">
                <div>
                    <span class="eyebrow">Veilig • Sneller • Overzichtelijk</span>
                    <h1>Meld incidenten sneller en beheer ze zonder chaos.</h1>
                    <p class="lead">
                        Een moderne oplossing voor medewerkers om storingen, veiligheidsrisico’s en serviceproblemen direct te melden,
                        te prioriteren en te volgen tot de juiste actie is genomen.
                    </p>

                    <div class="cta-row">
                        @auth
                        <a class="btn btn-primary" href="{{ route('incidents.create') }}">Nieuwe melding</a>
                        @else
                        <a class="btn btn-primary" href="{{ route('register') }}">Nieuwe melding</a>
                        @endauth
                        <a class="btn btn-outline" href="#workflow">Bekijk werkwijze</a>
                    </div>

                    <div class="mini-stats">
                        <div><strong>{{ $incidentCount }}</strong> Totaal gemeld</div>
                        <div><strong>{{ $openIncidentCount }}</strong> Openstaand</div>
                        <div><strong>{{ $resolvedPercentage }}%</strong> Opgelost</div>
                    </div>
                </div>

                <div class="dashboard">
                    <div class="dashboard-header">
                        <div>
                            <p>Dashboard</p>
                            <h3>Incident overzicht</h3>
                        </div>
                        <span class="status-badge">Online</span>
                    </div>

                    <div class="panel-box">
                        <div class="open-row">
                            <span>Open meldingen</span>
                            <strong>{{ $openIncidentCount }}</strong>
                        </div>
                        <div class="meter"><span @style(['width'=> ($incidentCount === 0 ? 0 : 100 - $resolvedPercentage).'%;'])></span></div>
                    </div>

                    <div class="incident-list">
                        @forelse ($recentIncidents as $incident)
                        <div class="incident-item">
                            <div>
                                <strong>{{ $incident->title }}</strong>
                                <p>Locatie: {{ $incident->location }}</p>
                            </div>
                            <span class="tag info">{{ $incident->status }}</span>
                        </div>
                        @empty
                        <div class="incident-item">
                            <strong>Nog geen incidenten gemeld</strong>
                        </div>
                        @endforelse
                    </div>
                </div>
            </section>

            <section id="stats" class="stats-grid">
                <div class="stat-card">
                    <div class="label">Meldingen</div>
                    <strong>{{ $incidentCount }}</strong>
                    <span>in totaal geregistreerd</span>
                </div>

                <div class="stat-card">
                    <div class="label">Openstaand</div>
                    <strong>{{ $openIncidentCount }}</strong>
                    <span>meldingen wachten op actie</span>
                </div>

                <div class="stat-card">
                    <div class="label">Opgelost</div>
                    <strong>{{ $resolvedPercentage }}%</strong>
                    <span>van alle geregistreerde meldingen</span>
                </div>
            </section>

            <section id="features" class="feature-grid">
                <div class="feature-card">
                    <div class="feature-icon" style="background: rgba(244,63,94,0.12); color: #fda4af;">⚡</div>
                    <h4>Snelle melding</h4>
                    <p>Meld een probleem in enkele seconden met prioriteit, locatie en beschrijving.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon" style="background: rgba(56,189,248,0.12); color: #7dd3fc;">📍</div>
                    <h4>Locatiebepaling</h4>
                    <p>Zet meldingen direct op de juiste afdeling of locatie voor snellere respons.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon" style="background: rgba(245,158,11,0.12); color: #fcd34d;">🔔</div>
                    <h4>Notificaties</h4>
                    <p>Automatische updates naar betrokken medewerkers en leidinggevenden.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon" style="background: rgba(52,211,153,0.12); color: #a7f3d0;">📊</div>
                    <h4>Rapportage</h4>
                    <p>Gebruik duidelijke statistieken om trends en risico's vroegtijdig te signaleren.</p>
                </div>
            </section>

            <section id="workflow" class="feature-grid" style="margin-top: 80px;">
                <div class="feature-card">
                    <div class="feature-icon" style="background: rgba(239,68,68,0.12); color: #fda4af;">1</div>
                    <h4>Melden</h4>
                    <p>Persoon meldt een incident met details en prioriteit.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon" style="background: rgba(56,189,248,0.12); color: #7dd3fc;">2</div>
                    <h4>Classificeren</h4>
                    <p>Systeem bepaalt urgentie en stuurt naar juiste team.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon" style="background: rgba(245,158,11,0.12); color: #fcd34d;">3</div>
                    <h4>Oplossen</h4>
                    <p>Het team volgt de zaak op en registreert acties.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon" style="background: rgba(52,211,153,0.12); color: #a7f3d0;">4</div>
                    <h4>Afronden</h4>
                    <p>Melding wordt gesloten met duidelijke status en bewijs.</p>
                </div>
            </section>

            <section class="cta-banner">
                <p>Klaar om te starten?</p>
                <h3>Maak vandaag nog je eerste incidentmelding.</h3>
                <p class="lead">Een duidelijk overzicht, snellere respons en beter beheer van alle meldingen in één systeem.</p>

                <div class="cta-row">
                    <a class="btn btn-primary" href="{{ route('register') }}">Melding starten</a>
                    <a class="btn btn-outline" href="#features">Bekijk functies</a>
                </div>
            </section>
        </main>
    </div>
</body>

</html>