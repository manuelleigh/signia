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
        
        return Inertia::render('Dashboard', [
            'agency' => $agency,
            'companiesCount' => $agency ? $agency->companies()->count() : 0,
            'companiesProdCount' => $agency ? $agency->companies()->where('environment', 'production')->count() : 0,
            'companiesDemoCount' => $agency ? $agency->companies()->where('environment', 'demo')->count() : 0,
            'companiesWithCertCount' => $agency ? $agency->companies()->has('certificate')->count() : 0,
            'documentsCount' => $agency ? Document::where('agency_id', $agency->id)->count() : 0,
            'apiTokens' => $request->user()->tokens()->select('id', 'name', 'last_used_at', 'created_at')->get()
        ]);
    }
}
