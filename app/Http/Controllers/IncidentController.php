<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use App\Models\IncidentType;
use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IncidentController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'type' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
        ]);

        $query = Incident::with(['user', 'assignedTo'])
            ->when($filters['type'] ?? null, fn (Builder $query, string $type) => $query->where('type', $type))
            ->when($filters['status'] ?? null, fn (Builder $query, string $status) => $query->where('status', $status))
            ->when($filters['location'] ?? null, fn (Builder $query, string $location) => $query->where('location', $location))
            ->when($filters['date_from'] ?? null, fn (Builder $query, string $date) => $query->whereDate('occurred_at', '>=', $date))
            ->when($filters['date_to'] ?? null, fn (Builder $query, string $date) => $query->whereDate('occurred_at', '<=', $date));

        $incidents = $query->latest('occurred_at')->get();

        return view('incidents.index', [
            'incidents' => $incidents,
            'incidentTypes' => IncidentType::orderBy('name')->get(),
            'locations' => Location::orderBy('name')->get(),
            'statuses' => Incident::query()->select('status')->distinct()->orderBy('status')->pluck('status'),
            'responsibles' => User::orderBy('name')->get(),
            'filters' => $filters,
        ]);
    }

    public function create(): View
    {
        return view('incidents.create', [
            'incidentTypes' => IncidentType::where('active', true)->orderBy('name')->get(),
            'locations' => Location::where('active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'location' => ['required', 'string', 'max:255'],
            'occurred_at' => ['required', 'date'],
            'type' => ['required', 'string', 'max:255'],
        ]);

        $request->user()->incidents()->create([
            ...$validated,
            'status' => 'Open',
        ]);

        return redirect('/')->with('status', 'Je incident is succesvol gemeld.');
    }
}
