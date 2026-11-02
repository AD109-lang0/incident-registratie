<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use Illuminate\View\View;

class CoordinatorDashboardController extends Controller
{
    public function index(): View
    {
        $incidentTypeCounts = Incident::query()
            ->select('type')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('type')
            ->orderBy('type')
            ->get();

        $locationCounts = Incident::query()
            ->select('location')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('location')
            ->orderBy('location')
            ->get();

        $monthlyIncidentCounts = Incident::query()
            ->selectRaw('DATE(occurred_at) as incident_date, COUNT(*) as total')
            ->groupByRaw('DATE(occurred_at)')
            ->orderBy('incident_date')
            ->get()
            ->groupBy(fn (Incident $incident) => substr($incident->incident_date, 0, 7))
            ->map(fn ($dailyCounts) => $dailyCounts->sum('total'));

        return view('coordinator.dashboard', [
            'incidentTypeCounts' => $incidentTypeCounts,
            'locationCounts' => $locationCounts,
            'monthlyIncidentCounts' => $monthlyIncidentCounts,
        ]);
    }
}
