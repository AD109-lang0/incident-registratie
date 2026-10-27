<?php

use App\Models\IncidentType;
use App\Models\Location;
use App\Models\User;
use App\UserRole;
use Database\Seeders\DatabaseSeeder;

test('the application returns a successful response', function () {
    $user = User::factory()->create();
    $user->incidents()->createMany([
        [
            'title' => 'Eerste incident',
            'description' => 'Beschrijving van het eerste incident.',
            'location' => 'Kantoor',
            'occurred_at' => '2026-09-16 09:00:00',
            'type' => 'Technisch',
            'status' => 'Open',
        ],
        [
            'title' => 'Tweede incident',
            'description' => 'Beschrijving van het tweede incident.',
            'location' => 'Magazijn',
            'occurred_at' => '2026-09-16 10:00:00',
            'type' => 'Veiligheid',
            'status' => 'Open',
        ],
    ]);

    $response = $this->get('/');

    $response->assertStatus(200)
        ->assertSee('<strong>2</strong>', false);
});

test('visitors can browse active incident types and their incident counts', function () {
    $activeType = IncidentType::create([
        'name' => 'Openbare categorie '.uniqid(),
        'active' => true,
    ]);
    $inactiveType = IncidentType::create([
        'name' => 'Verborgen categorie '.uniqid(),
        'active' => false,
    ]);

    $user = User::factory()->create();
    $user->incidents()->create([
        'title' => 'Melding bij categorie',
        'description' => 'Een testmelding.',
        'location' => 'Kantoor',
        'occurred_at' => '2026-09-16 09:00:00',
        'type' => $activeType->name,
        'status' => 'Open',
    ]);

    $this->get(route('incidenttypes'))
        ->assertOk()
        ->assertSee($activeType->name)
        ->assertSee('1 melding')
        ->assertDontSee($inactiveType->name);
});

test('the login page is available', function () {
    $response = $this->get('/login');

    $response->assertStatus(200)
        ->assertSee('Inloggen')
        ->assertSee('Wachtwoord');
});

test('successful login displays a success message', function () {
    User::factory()->create([
        'email' => 'login-flash@example.com',
        'password' => 'secret123',
    ]);

    $this->followingRedirects()
        ->post('/login', [
            'email' => 'login-flash@example.com',
            'password' => 'secret123',
        ])
        ->assertSee('Je bent succesvol ingelogd.');
});

test('failed login displays a clear error message', function () {
    $this->followingRedirects()
        ->post('/login', [
            'email' => 'missing-flash@example.com',
            'password' => 'wrong-password',
        ])
        ->assertSee('De opgegeven inloggegevens zijn onjuist.');
});

test('successful logout displays a success message', function () {
    $user = User::factory()->create();

    $this->followingRedirects()
        ->actingAs($user)
        ->post('/logout')
        ->assertSee('Je bent uitgelogd.');
});

test('a user can register and be saved to the database', function () {
    $response = $this->post('/register', [
        'name' => 'Test Gebruiker',
        'email' => 'test@example.com',
        'password' => 'secret123',
        'password_confirmation' => 'secret123',
    ]);

    $response->assertRedirect('/login')
        ->assertSessionHas('status', 'Je account is aangemaakt en je bent ingelogd.');
    $this->assertDatabaseHas('users', [
        'email' => 'test@example.com',
        'name' => 'Test Gebruiker',
        'role' => UserRole::Melder->value,
    ]);
    $this->assertTrue(User::where('email', 'test@example.com')->exists());
});

test('users can have each supported role', function () {
    expect(User::factory()->user()->make()->role)->toBe(UserRole::User)
        ->and(User::factory()->make()->role)->toBe(UserRole::Melder)
        ->and(User::factory()->coordinator()->make()->role)->toBe(UserRole::Coordinator)
        ->and(User::factory()->beheerder()->make()->role)->toBe(UserRole::Beheerder);
});

test('the database seeder updates the admin user password for login', function () {
    User::factory()->create([
        'email' => 'test@example.com',
        'password' => 'old-password',
    ]);

    $this->artisan('db:seed', ['--class' => DatabaseSeeder::class])->assertSuccessful();

    $this->assertDatabaseHas('users', [
        'email' => 'test@example.com',
        'role' => UserRole::Beheerder->value,
    ]);

    $this->post('/login', [
        'email' => 'test@example.com',
        'password' => 'password123!',
    ])->assertRedirect('/');

    $this->assertAuthenticatedAs(User::where('email', 'test@example.com')->firstOrFail());
});

test('the database seeder creates one account for each supported role', function () {
    $this->artisan('db:seed', ['--class' => DatabaseSeeder::class])->assertSuccessful();
    $this->artisan('db:seed', ['--class' => DatabaseSeeder::class])->assertSuccessful();

    expect(User::query()->whereIn('email', [
        'test@example.com',
        'melder@example.com',
        'coordinator@example.com',
        'user@example.com',
    ])->count())->toBe(4);

    $this->assertDatabaseHas('users', ['email' => 'test@example.com', 'role' => UserRole::Beheerder->value]);
    $this->assertDatabaseHas('users', ['email' => 'melder@example.com', 'role' => UserRole::Melder->value]);
    $this->assertDatabaseHas('users', ['email' => 'coordinator@example.com', 'role' => UserRole::Coordinator->value]);
    $this->assertDatabaseHas('users', ['email' => 'user@example.com', 'role' => UserRole::User->value]);
});

test('an authenticated user can create an incident', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->post('/incidents', [
            'title' => 'Netwerk uitval',
            'description' => 'Het netwerk in de kantine werkt niet.',
            'location' => 'Kantine',
            'occurred_at' => '2026-09-09T14:30',
            'type' => 'Infrastructuur',
        ]);

    $response->assertRedirect('/');
    $this->assertDatabaseHas('incidents', [
        'title' => 'Netwerk uitval',
        'location' => 'Kantine',
        'type' => 'Infrastructuur',
        'user_id' => $user->id,
    ]);
});

test('an authenticated user can view all incidents and statuses', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $user->incidents()->create([
        'title' => 'Netwerk uitval',
        'description' => 'Het netwerk in de kantine werkt niet.',
        'location' => 'Kantine',
        'occurred_at' => '2026-09-09 14:30:00',
        'type' => 'Infrastructuur',
        'status' => 'In behandeling',
    ]);

    $otherUser->incidents()->create([
        'title' => 'Ander incident',
        'description' => 'Niet van deze gebruiker.',
        'location' => 'Magazijn',
        'occurred_at' => '2026-09-09 15:00:00',
        'type' => 'Veiligheid',
        'status' => 'Open',
    ]);

    $response = $this->actingAs($user)->get('/incidents');

    $response->assertOk()
        ->assertSee('Alle incidenten')
        ->assertSee('Netwerk uitval')
        ->assertSee('In behandeling')
        ->assertSee('Ander incident')
        ->assertSee($otherUser->name);
});

test('only a beheerder can update or delete incidents', function () {
    $beheerder = User::factory()->beheerder()->create();
    $melder = User::factory()->create();
    $incident = $melder->incidents()->create([
        'title' => 'Printer defect',
        'description' => 'De printer werkt niet.',
        'location' => 'Receptie',
        'occurred_at' => '2026-09-09 14:30:00',
        'type' => 'Hardware',
        'status' => 'Open',
    ]);

    $this->actingAs($melder)
        ->patch(route('beheer.incidents.update', $incident), [
            'title' => 'Aangepast door melder',
            'description' => 'Aangepaste omschrijving.',
            'location' => 'Receptie',
            'occurred_at' => '2026-09-09 14:30:00',
            'type' => 'Hardware',
            'status' => 'Opgelost',
        ])
        ->assertForbidden();

    $this->actingAs($beheerder)
        ->patch(route('beheer.incidents.update', $incident), [
            'title' => 'Aangepast door beheerder',
            'description' => 'Aangepaste omschrijving.',
            'location' => 'Receptie',
            'occurred_at' => '2026-09-09 14:30:00',
            'type' => 'Hardware',
            'status' => 'Opgelost',
        ])
        ->assertRedirect(route('incidents.index'));

    $this->assertDatabaseHas('incidents', [
        'id' => $incident->id,
        'title' => 'Aangepast door beheerder',
        'status' => 'Opgelost',
    ]);

    $this->actingAs($melder)
        ->delete(route('beheer.incidents.destroy', $incident))
        ->assertForbidden();

    $this->actingAs($beheerder)
        ->delete(route('beheer.incidents.destroy', $incident))
        ->assertRedirect(route('incidents.index'));

    $this->assertDatabaseMissing('incidents', ['id' => $incident->id]);
});

test('a coordinator can filter, assign and update incident handling', function () {
    $coordinator = User::factory()->coordinator()->create();
    $responsible = User::factory()->create(['name' => 'Verantwoordelijke Gebruiker']);
    $otherIncident = $responsible->incidents()->create([
        'title' => 'Ander incident',
        'description' => 'Ander incident.',
        'location' => 'Magazijn',
        'occurred_at' => '2026-09-10 10:00:00',
        'type' => 'Veiligheid',
        'status' => 'Open',
    ]);
    $incident = $responsible->incidents()->create([
        'title' => 'Netwerkprobleem',
        'description' => 'Het netwerk valt uit.',
        'location' => 'Kantoor',
        'occurred_at' => '2026-09-11 10:00:00',
        'type' => 'Infrastructuur',
        'status' => 'Open',
    ]);

    $this->actingAs($coordinator)
        ->get('/incidents?type=Infrastructuur&date_from=2026-09-11&status=Open&location=Kantoor')
        ->assertOk()
        ->assertSee('Netwerkprobleem')
        ->assertDontSee('Ander incident');

    $this->actingAs($coordinator)
        ->patch(route('beheer.incidents.update', $incident), [
            'title' => $incident->title,
            'description' => $incident->description,
            'location' => $incident->location,
            'occurred_at' => '2026-09-11 10:00:00',
            'type' => $incident->type,
            'status' => 'In behandeling',
            'assigned_to_user_id' => $responsible->id,
            'notes' => 'Coördinator heeft contact opgenomen.',
        ])
        ->assertRedirect(route('incidents.index'));

    $this->assertDatabaseHas('incidents', [
        'id' => $incident->id,
        'status' => 'In behandeling',
        'assigned_to_user_id' => $responsible->id,
        'notes' => 'Coördinator heeft contact opgenomen.',
    ]);

    $this->actingAs($responsible)
        ->patch(route('beheer.incidents.update', $otherIncident), [])
        ->assertForbidden();
});

test('only a beheerder can manage catalogs and user roles', function () {
    $beheerder = User::factory()->beheerder()->create();
    $user = User::factory()->create();
    $typeName = 'Veiligheid '.uniqid();
    $locationName = 'Hoofdkantoor '.uniqid();

    $this->actingAs($user)->get('/beheer')->assertForbidden();

    $this->actingAs($beheerder)->post('/beheer/incidenttypes', ['name' => $typeName])->assertRedirect();
    $this->actingAs($beheerder)->post('/beheer/locaties', ['name' => $locationName])->assertRedirect();

    $incidentType = IncidentType::where('name', $typeName)->firstOrFail();
    $location = Location::where('name', $locationName)->firstOrFail();
    $this->assertDatabaseHas('incident_types', ['name' => $typeName]);
    $this->assertDatabaseHas('locations', ['name' => $locationName]);

    $this->actingAs($beheerder)->patch("/beheer/incidenttypes/{$incidentType->id}", ['name' => $typeName.' aangepast'])->assertRedirect();
    $this->actingAs($beheerder)->patch("/beheer/locaties/{$location->id}", ['name' => $locationName.' aangepast'])->assertRedirect();
    $this->assertDatabaseHas('incident_types', ['name' => $typeName.' aangepast']);
    $this->assertDatabaseHas('locations', ['name' => $locationName.' aangepast']);

    $this->actingAs($beheerder)->delete("/beheer/incidenttypes/{$incidentType->id}")->assertRedirect();
    $this->actingAs($beheerder)->delete("/beheer/locaties/{$location->id}")->assertRedirect();
    $this->assertDatabaseMissing('incident_types', ['id' => $incidentType->id]);
    $this->assertDatabaseMissing('locations', ['id' => $location->id]);

    $this->actingAs($beheerder)->patch("/beheer/gebruikers/{$user->id}/rol", [
        'role' => UserRole::Coordinator->value,
    ])->assertRedirect();

    expect($user->refresh()->role)->toBe(UserRole::Coordinator);
});

test('a melder must provide all required incident fields', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/incidents', [
        'title' => '',
        'description' => '',
        'location' => '',
        'occurred_at' => '',
        'type' => '',
    ]);

    $response->assertSessionHasErrors(['title', 'description', 'location', 'occurred_at', 'type']);
    $this->assertDatabaseMissing('incidents', ['user_id' => $user->id]);
});
