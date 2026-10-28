<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use App\Models\IncidentType;
use Illuminate\View\View;

class IncidentTypeController extends Controller
{
    public function index(): View
    {
        $incidentTypes = IncidentType::query()
            ->where('active', true)
            ->orderBy('name')
            ->get();

        return view('types.index', [
            'incidentTypes' => $incidentTypes,
            'incidentCounts' => Incident::query()
                ->selectRaw('type, COUNT(*) as aggregate')
                ->groupBy('type')
                ->pluck('aggregate', 'type'),
            'incidentCount' => Incident::query()->count(),
        ]);
    }
}
