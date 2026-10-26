<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use App\Models\IncidentType;
use App\Models\Location;
use App\Models\User;
use App\UserRole;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BeheerController extends Controller
{
    public function index(): View
    {
        return view('beheer.index', [
            'incidentTypes' => IncidentType::orderBy('name')->get(),
            'locations' => Location::orderBy('name')->get(),
            'users' => User::orderBy('name')->get(),
            'roles' => UserRole::cases(),
        ]);
    }

    public function editIncident(Incident $incident): View
    {
        return view('beheer.incidents.edit', [
            'incident' => $incident->load('assignedTo'),
            'responsibles' => User::orderBy('name')->get(),
        ]);
    }

    public function updateIncident(Request $request, Incident $incident): RedirectResponse
    {
        $incident->update($request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'location' => ['required', 'string', 'max:255'],
            'occurred_at' => ['required', 'date'],
            'type' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'assigned_to_user_id' => ['nullable', 'exists:users,id'],
            'notes' => ['nullable', 'string'],
        ]));

        return redirect()->route('incidents.index')->with('status', 'Incident bijgewerkt.');
    }

    public function destroyIncident(Incident $incident): RedirectResponse
    {
        $incident->delete();

        return redirect()->route('incidents.index')->with('status', 'Incident verwijderd.');
    }

    public function storeIncidentType(Request $request): RedirectResponse
    {
        IncidentType::create($request->validate(['name' => ['required', 'string', 'max:255', 'unique:incident_types,name']]));

        return back()->with('status', 'Incidenttype toegevoegd.');
    }

    public function updateIncidentType(Request $request, IncidentType $incidentType): RedirectResponse
    {
        $incidentType->update($request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('incident_types', 'name')->ignore($incidentType)],
        ]));

        return back()->with('status', 'Incidenttype bijgewerkt.');
    }

    public function destroyIncidentType(IncidentType $incidentType): RedirectResponse
    {
        $incidentType->delete();

        return back()->with('status', 'Incidenttype verwijderd.');
    }

    public function storeLocation(Request $request): RedirectResponse
    {
        Location::create($request->validate(['name' => ['required', 'string', 'max:255', 'unique:locations,name']]));

        return back()->with('status', 'Locatie toegevoegd.');
    }

    public function updateLocation(Request $request, Location $location): RedirectResponse
    {
        $location->update($request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('locations', 'name')->ignore($location)],
        ]));

        return back()->with('status', 'Locatie bijgewerkt.');
    }

    public function destroyLocation(Location $location): RedirectResponse
    {
        $location->delete();

        return back()->with('status', 'Locatie verwijderd.');
    }

    public function updateUserRole(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate(['role' => ['required', Rule::enum(UserRole::class)]]);
        $user->role = UserRole::from($validated['role']);
        $user->save();

        return back()->with('status', 'Gebruikersrol bijgewerkt.');
    }
}
