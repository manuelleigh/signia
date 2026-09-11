<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Document;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $agency = $request->user()->agency;
        
        $chartData = [];
        if ($agency) {
            // Generar los ultimos 15 dias
            $dates = collect();
            for ($i = 14; $i >= 0; $i--) {
                $dates->push(now()->subDays($i)->format('Y-m-d'));
            }

            // Traer conteo agrupado por fecha y status
            $docs = Document::where('agency_id', $agency->id)
                ->where('created_at', '>=', now()->subDays(14)->startOfDay())
                ->selectRaw('DATE(created_at) as date, status, COUNT(*) as count')
                ->groupBy('date', 'status')
                ->get();

            $datasets = [
                'accepted' => [],
                'rejected' => [],
                'exception' => [],
            ];

            foreach ($dates as $date) {
                $dayDocs = $docs->where('date', $date);
                $datasets['accepted'][] = $dayDocs->whereIn('status', ['accepted', 'accepted_with_observations'])->sum('count');
                $datasets['rejected'][] = $dayDocs->where('status', 'rejected')->sum('count');
                $datasets['exception'][] = $dayDocs->where('status', 'exception')->sum('count');
            }

            $chartData = [
                'labels' => $dates->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m'))->toArray(),
                'accepted' => $datasets['accepted'],
                'rejected' => $datasets['rejected'],
                'exception' => $datasets['exception'],
            ];
        }

        return Inertia::render('Dashboard', [
            'agency' => $agency,
            'companiesCount' => $agency ? $agency->companies()->count() : 0,
            'companiesProdCount' => $agency ? $agency->companies()->where('environment', 'production')->count() : 0,
            'companiesDemoCount' => $agency ? $agency->companies()->where('environment', 'demo')->count() : 0,
            'companiesWithCertCount' => $agency ? $agency->companies()->has('certificate')->count() : 0,
            'documentsCount' => $agency ? Document::where('agency_id', $agency->id)->count() : 0,
            'chartData' => $chartData,
            'apiTokens' => $request->user()->tokens()->select('id', 'name', 'last_used_at', 'created_at')->get()
        ]);
    }
}
