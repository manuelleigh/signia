<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $agency = $request->user()->agency;
        $companies = $agency ? $agency->companies()->with('certificate')->latest()->get() : [];

        return Inertia::render('Companies/Index', [
            'companies' => $companies
        ]);
    }

    public function create()
    {
        return Inertia::render('Companies/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'ruc' => 'required|string|size:11',
            'business_name' => 'required|string|max:255',
            'environment' => 'required|in:demo,production',
            'engine_type' => 'required|in:qpse,native',
        ]);

        $agency = $request->user()->agency;
        if (!$agency) {
            return redirect()->back()->withErrors(['error' => 'No tienes una agencia asignada.']);
        }

        $agency->companies()->create($request->all());

        return redirect()->route('companies.index')->with('success', 'RUC registrado exitosamente.');
    }
}
