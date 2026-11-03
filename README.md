# Incidentregistratie

Laravel-applicatie voor het melden, opvolgen en beheren van incidenten. De interface en meldingen zijn Nederlandstalig.

## Functionaliteit

- Incidenten melden met type, locatie, datum en beschrijving.
- Incidenten filteren en opvolgen met de statussen Nieuw, In onderzoek, Opgelost en Gesloten.
- Verantwoordelijken toewijzen en interne notities toevoegen.
- Beheercatalogi onderhouden voor incidenttypen en locaties.
- Inzicht geven in aantallen per type, maand en locatie op het coördinatordashboard.

## Rollen

| Rol | Toegang |
| --- | --- |
| User | Incidenten melden en eigen incidenten bekijken. |
| Melder | Incidenten melden en alle incidenten bekijken. |
| Coördinator | Alle incidenten bekijken, filteren, toewijzen en opvolgen; toegang tot het coördinatordashboard. |
| Beheerder | Alle incidenten bekijken en beheren, en gebruikersrollen, incidenttypen en locaties beheren. |

Een coördinator krijgt geen toegang tot beheerfuncties die alleen voor beheerders bedoeld zijn.

## Techniek

- PHP 8.3 of hoger
- Laravel 13
- SQLite als standaarddatabase
- Vite en Tailwind CSS 4 voor de frontend
- Pest voor tests

## Lokaal starten

Installeer PHP, Composer, Node.js en npm. Stel vervolgens de lokale omgeving in:

```bash
composer setup
```

Dit Composer-script installeert de PHP- en npm-pakketten, maakt zo nodig `.env` aan, genereert een applicatiesleutel, voert migraties uit en bouwt de frontend. De standaardconfiguratie gebruikt SQLite; zorg dat `database/database.sqlite` bestaat voordat de migraties draaien.

Start daarna de ontwikkelomgeving:

```bash
composer run dev
```

De ontwikkelomgeving start de Laravel-server, de queue-worker en Vite. De applicatie is standaard beschikbaar op `http://localhost:8000`.

### Demo-accounts

Maak de standaarddemo-accounts aan met:

```bash
php artisan db:seed
```

Deze lokale demo-accounts gebruiken het wachtwoord `password123!`:

| Rol | E-mailadres |
| --- | --- |
| Beheerder | `test@example.com` |
| Melder | `melder@example.com` |
| Coördinator | `coordinator@example.com` |
| User | `user@example.com` |

Gebruik deze accounts en wachtwoorden uitsluitend lokaal; wijzig of verwijder ze voordat de applicatie in een gedeelde of productieomgeving wordt gebruikt.

## Belangrijke routes

| Route | Beschrijving | Toegang |
| --- | --- | --- |
| `/` | Overzicht en recente incidenten | Publiek; eigen incidenten voor ingelogde melders |
| `/login` en `/register` | Inloggen en registreren | Publiek |
| `/incidents` | Incidentenlijst en filters | Ingelogde gebruikers |
| `/incidents/create` | Een incident melden | Ingelogde gebruikers |
| `/coordinator/dashboard` | Grafieken per type, maand en locatie | Coördinator |
| `/beheer` | Catalogi en gebruikersrollen beheren | Beheerder |

## Tests

Voer de volledige testsuite uit met:

```bash
composer test
```

Of rechtstreeks met Artisan:

```bash
php artisan test
```
