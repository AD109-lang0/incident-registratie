<?php

use App\IncidentStatus;
use App\Models\IncidentType;
use App\Models\Location;
use App\Models\User;
use App\UserRole;

dataset('every user role', [
    'user' => [UserRole::User],
    'melder' => [UserRole::Melder],
    'coordinator' => [UserRole::Coordinator],
    'beheerder' => [UserRole::Beheerder],
]);

dataset('roles denied administrator access', [
    'user' => [UserRole::User],
    'melder' => [UserRole::Melder],
    'coordinator' => [UserRole::Coordinator],
]);

test('guests are redirected from authenticated incident and management routes', function () {
    $owner = User::factory()->create();
    $incident = $owner->incidents()->create([
        'title' => 'Beveiligde melding',
        'description' => 'Een incident dat alleen ingelogd beschikbaar is.',
        'location' => 'Kantoor',
        'occurred_at' => '2026-09-16 09:00:00',
        'type' => 'Infrastructuur',
        'status' => IncidentStatus::Nieuw->value,
    ]);

    $this->get(route('incidents.index'))->assertRedirect(route('login'));
    $this->get(route('incidents.create'))->assertRedirect(route('login'));
    $this->post(route('incidents.store'), [])->assertRedirect(route('login'));
    $this->get(route('coordinator.dashboard'))->assertRedirect(route('login'));
    $this->get(route('beheer.incidents.edit', $incident))->assertRedirect(route('login'));
    $this->get(route('beheer.index'))->assertRedirect(route('login'));
});

test('every role can report an incident', function (UserRole $role) {
    $user = User::factory()->create(['role' => $role]);

    $response = $this->actingAs($user)->post(route('incidents.store'), [
        'title' => 'Netwerk uitval',
        'description' => 'Het netwerk werkt niet.',
        'location' => 'Kantine',
        'occurred_at' => '2026-09-09T14:30',
        'type' => 'Infrastructuur',
    ]);

    $response->assertRedirect('/');
    $this->assertDatabaseHas('incidents', [
        'title' => 'Netwerk uitval',
        'user_id' => $user->id,
        'status' => IncidentStatus::Nieuw->value,
    ]);
})->with('every user role');

test('users see only their own incident records', function (UserRole $role) {
    $user = User::factory()->create(['role' => $role]);
    $otherUser = User::factory()->create();

    $user->incidents()->create([
        'title' => 'Eigen incident',
        'description' => 'Een eigen melding.',
        'location' => 'Kantoor',
        'occurred_at' => '2026-09-16 09:00:00',
        'type' => 'Infrastructuur',
        'status' => IncidentStatus::Nieuw->value,
    ]);
    $otherUser->incidents()->create([
        'title' => 'Incident van een ander',
        'description' => 'Een melding van iemand anders.',
        'location' => 'Magazijn',
        'occurred_at' => '2026-09-16 10:00:00',
        'type' => 'Veiligheid',
        'status' => IncidentStatus::Nieuw->value,
    ]);

    $response = $this->actingAs($user)->get(route('incidents.index'));

    $response->assertOk()
        ->assertSee('Eigen incident');

    $response->assertDontSee('Incident van een ander');
})->with([
    'user' => [UserRole::User],
]);

test('melders, coordinators and administrators see all incident records', function (UserRole $role) {
    $user = User::factory()->create(['role' => $role]);
    $otherUser = User::factory()->create();

    $user->incidents()->create([
        'title' => 'Eigen incident',
        'description' => 'Een eigen melding.',
        'location' => 'Kantoor',
        'occurred_at' => '2026-09-16 09:00:00',
        'type' => 'Infrastructuur',
        'status' => IncidentStatus::Nieuw->value,
    ]);
    $otherUser->incidents()->create([
        'title' => 'Incident van een ander',
        'description' => 'Een melding van iemand anders.',
        'location' => 'Magazijn',
        'occurred_at' => '2026-09-16 10:00:00',
        'type' => 'Veiligheid',
        'status' => IncidentStatus::Nieuw->value,
    ]);

    $this->actingAs($user)
        ->get(route('incidents.index'))
        ->assertOk()
        ->assertSee('Eigen incident')
        ->assertSee('Incident van een ander');
})->with([
    'melder' => [UserRole::Melder],
    'coordinator' => [UserRole::Coordinator],
    'beheerder' => [UserRole::Beheerder],
]);

test('coordinators can access the coordinator dashboard', function () {
    $coordinator = User::factory()->coordinator()->create();

    $this->actingAs($coordinator)
        ->get(route('coordinator.dashboard'))
        ->assertOk()
        ->assertSee('Coördinator dashboard');
});

test('non-coordinator roles are forbidden from the coordinator dashboard', function (UserRole $role) {
    $user = User::factory()->create(['role' => $role]);

    $this->actingAs($user)
        ->get(route('coordinator.dashboard'))
        ->assertForbidden();
})->with([
    'user' => [UserRole::User],
    'melder' => [UserRole::Melder],
    'beheerder' => [UserRole::Beheerder],
]);

test('coordinators and administrators can edit and update incidents', function (UserRole $role) {
    $user = User::factory()->create(['role' => $role]);
    $owner = User::factory()->create();
    $incident = $owner->incidents()->create([
        'title' => 'Openstaande melding',
        'description' => 'Een melding om bij te werken.',
        'location' => 'Kantoor',
        'occurred_at' => '2026-09-16 09:00:00',
        'type' => 'Infrastructuur',
        'status' => IncidentStatus::Nieuw->value,
    ]);

    $editResponse = $this->actingAs($user)->get(route('beheer.incidents.edit', $incident));

    $editResponse->assertOk()
        ->assertSee('Openstaande melding');

    $this->actingAs($user)
        ->patch(route('beheer.incidents.update', $incident), [
            'title' => 'Bijgewerkte melding',
            'description' => 'De melding is opgevolgd.',
            'location' => 'Kantoor',
            'occurred_at' => '2026-09-16 09:00:00',
            'type' => 'Infrastructuur',
            'status' => IncidentStatus::InOnderzoek->value,
        ])
        ->assertRedirect(route('incidents.index'));

    $this->assertDatabaseHas('incidents', [
        'id' => $incident->id,
        'title' => 'Bijgewerkte melding',
        'status' => IncidentStatus::InOnderzoek->value,
    ]);
})->with([
    'coordinator' => [UserRole::Coordinator],
    'beheerder' => [UserRole::Beheerder],
]);

test('users and melders cannot edit or update incidents', function (UserRole $role) {
    $user = User::factory()->create(['role' => $role]);
    $owner = User::factory()->create();
    $incident = $owner->incidents()->create([
        'title' => 'Openstaande melding',
        'description' => 'Een melding om bij te werken.',
        'location' => 'Kantoor',
        'occurred_at' => '2026-09-16 09:00:00',
        'type' => 'Infrastructuur',
        'status' => IncidentStatus::Nieuw->value,
    ]);

    $this->actingAs($user)
        ->get(route('beheer.incidents.edit', $incident))
        ->assertForbidden();

    $this->actingAs($user)
        ->patch(route('beheer.incidents.update', $incident), [
            'title' => 'Onbevoegde wijziging',
            'description' => 'Deze wijziging mag niet worden opgeslagen.',
            'location' => 'Kantoor',
            'occurred_at' => '2026-09-16 09:00:00',
            'type' => 'Infrastructuur',
            'status' => IncidentStatus::Opgelost->value,
        ])
        ->assertForbidden();

    $this->assertDatabaseHas('incidents', [
        'id' => $incident->id,
        'title' => 'Openstaande melding',
        'status' => IncidentStatus::Nieuw->value,
    ]);
})->with([
    'user' => [UserRole::User],
    'melder' => [UserRole::Melder],
]);

test('non-administrators cannot view or mutate administrator resources', function (UserRole $role) {
    $user = User::factory()->create(['role' => $role]);
    $targetUser = User::factory()->create();
    $owner = User::factory()->create();
    $incident = $owner->incidents()->create([
        'title' => 'Niet te verwijderen melding',
        'description' => 'Deze melding moet behouden blijven.',
        'location' => 'Kantoor',
        'occurred_at' => '2026-09-16 09:00:00',
        'type' => 'Infrastructuur',
        'status' => IncidentStatus::Nieuw->value,
    ]);
    $incidentType = IncidentType::create(['name' => 'Bestaand type']);
    $location = Location::create(['name' => 'Bestaande locatie']);

    $this->actingAs($user)->get(route('beheer.index'))->assertForbidden();
    $this->actingAs($user)->delete(route('beheer.incidents.destroy', $incident))->assertForbidden();

    $this->actingAs($user)
        ->post(route('beheer.incidenttypes.store'), ['name' => 'Nieuw type'])
        ->assertForbidden();
    $this->actingAs($user)
        ->patch(route('beheer.incidenttypes.update', $incidentType), ['name' => 'Aangepast type'])
        ->assertForbidden();
    $this->actingAs($user)
        ->delete(route('beheer.incidenttypes.destroy', $incidentType))
        ->assertForbidden();

    $this->actingAs($user)
        ->post(route('beheer.locations.store'), ['name' => 'Nieuwe locatie'])
        ->assertForbidden();
    $this->actingAs($user)
        ->patch(route('beheer.locations.update', $location), ['name' => 'Aangepaste locatie'])
        ->assertForbidden();
    $this->actingAs($user)
        ->delete(route('beheer.locations.destroy', $location))
        ->assertForbidden();

    $this->actingAs($user)
        ->patch(route('beheer.users.role', $targetUser), ['role' => UserRole::Beheerder->value])
        ->assertForbidden();

    $this->assertModelExists($incident);
    $this->assertModelExists($incidentType);
    $this->assertModelExists($location);
    $this->assertDatabaseHas('incidents', [
        'id' => $incident->id,
        'title' => 'Niet te verwijderen melding',
    ]);
    $this->assertDatabaseHas('incident_types', ['name' => 'Bestaand type']);
    $this->assertDatabaseHas('locations', ['name' => 'Bestaande locatie']);
    $this->assertDatabaseMissing('incident_types', ['name' => 'Nieuw type']);
    $this->assertDatabaseMissing('locations', ['name' => 'Nieuwe locatie']);
    expect($targetUser->refresh()->role)->toBe(UserRole::Melder);
})->with('roles denied administrator access');

test('registration cannot assign a privileged role through request input', function () {
    $this->post(route('register.post'), [
        'name' => 'Nieuwe gebruiker',
        'email' => 'new-user@example.com',
        'password' => 'secret123',
        'password_confirmation' => 'secret123',
        'role' => UserRole::Beheerder->value,
    ])->assertRedirect(route('login'));

    $this->assertDatabaseHas('users', [
        'email' => 'new-user@example.com',
        'role' => UserRole::Melder->value,
    ]);
});

test('administrators cannot assign an unsupported user role', function () {
    $administrator = User::factory()->beheerder()->create();
    $user = User::factory()->create();

    $this->actingAs($administrator)
        ->patch(route('beheer.users.role', $user), ['role' => 'superadmin'])
        ->assertSessionHasErrors('role');

    expect($user->refresh()->role)->toBe(UserRole::Melder);
});
