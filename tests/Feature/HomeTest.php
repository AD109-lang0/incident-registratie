<?php

use App\Models\User;

test('users still see their incident after logging out and back in', function () {
    $user = User::factory()->create([
        'email' => 'incident-owner@example.com',
        'password' => 'secret123',
    ]);

    $this->post(route('login.post'), [
        'email' => 'incident-owner@example.com',
        'password' => 'secret123',
    ])->assertRedirect('/');

    $this->post(route('incidents.store'), [
        'title' => 'Printer werkt niet',
        'description' => 'De printer op kantoor reageert niet.',
        'location' => 'Kantoor',
        'occurred_at' => '2026-09-16T09:00',
        'type' => 'Technisch',
    ])->assertRedirect('/');

    $this->post(route('logout'))->assertRedirect('/');
    $this->assertGuest();

    $this->post(route('login.post'), [
        'email' => 'incident-owner@example.com',
        'password' => 'secret123',
    ])->assertRedirect('/');

    $this->assertAuthenticatedAs($user);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Printer werkt niet')
        ->assertDontSee('Nog geen incidenten gemeld');

    $this->get(route('incidents.index'))
        ->assertOk()
        ->assertSee('Printer werkt niet')
        ->assertDontSee('Er zijn nog geen incidenten gemeld.');
});
