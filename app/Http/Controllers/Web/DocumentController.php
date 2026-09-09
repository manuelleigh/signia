<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Document;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $agency = $request->user()->agency;
        if (!$agency) {
            abort(403, 'No tienes una agencia vinculada.');
        }

        $query = Document::where('agency_id', $agency->id)->with('company');

        // Advanced Filtering
        if ($request->filled('environment')) {
            $query->whereHas('company', function ($q) use ($request) {
                $q->where('environment', $request->environment);
            });
        }

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        if ($request->filled('document_type')) {
            $query->where('document_type', $request->document_type);
        }

        // Date Period Filtering
        $period = $request->get('period', 'month');
        
        if ($period === 'month' && $request->filled('month')) {
            $query->whereMonth('created_at', date('m', strtotime($request->month)))
                  ->whereYear('created_at', date('Y', strtotime($request->month)));
        } elseif ($period === 'date' && $request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        } elseif ($period === 'between_months' && $request->filled('month_start') && $request->filled('month_end')) {
            $start = date('Y-m-01 00:00:00', strtotime($request->month_start));
            $end = date('Y-m-t 23:59:59', strtotime($request->month_end));
            $query->whereBetween('created_at', [$start, $end]);
        } elseif ($period === 'between_dates' && $request->filled('date_start') && $request->filled('date_end')) {
            $start = $request->date_start . ' 00:00:00';
            $end = $request->date_end . ' 23:59:59';
            $query->whereBetween('created_at', [$start, $end]);
        }

        $documents = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        $companies = $agency->companies()->select('id', 'business_name', 'ruc')->orderBy('business_name')->get();

        return Inertia::render('Documents/Index', [
            'documents' => $documents,
            'companies' => $companies,
            'filters' => $request->only(['period', 'month', 'date', 'month_start', 'month_end', 'date_start', 'date_end', 'document_type', 'environment', 'company_id'])
        ]);
    }
}
