<?php

namespace App\Http\Controllers;

use App\Exports\ActiveOrdersExport;
use App\Exports\ProductionPlanExport;
use App\Imports\DeliveriesImport;
use App\Imports\ProductionImport;
use App\Imports\ProductionPlanImport;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ReportsController extends Controller
{
    /**
     * Display to the user the all reports page
     *
     * @return view
     */
    public function index()
    {
        return view('reports.index');
    }

    /**
     * Export the active orders list
     *
     * @return void
     */
    public function exportActiveOrders()
    {
        $user = auth()->user()->name;
        $now = Carbon::now()->format('d.m.Y Hms');
        $filename = 'comenzi active exportat de '. $user . ' in ' . $now . '.xlsx';
        Excel::store(new ActiveOrdersExport, $filename, 'public');
        return Excel::download(new ActiveOrdersExport, 'comenzi active.xlsx');
    }

    /**
     * Export the production plan
     *
     * @return void
     */
    public function exportProductionPlan()
    {
        $user = auth()->user()->name;
        $now = Carbon::now()->format('d.m.Y Hms');
        $filename = 'plan de productie exportat de ' . $user . ' in ' . $now . '.xlsx';
        Excel::store(new ProductionPlanExport, $filename, 'exports');
        dd(Storage::url($filename));
        return Excel::download(new ProductionPlanExport, 'plan de productie.xlsx');
    }

    /**
     * Shows the import production page
     *
     * @return view
     */
    public function imports()
    {
        return view('reports.import.index');
    }

    /**
     * Import the production
     *
     * @return redirect
     */
    public function importProduction()
    {
        try {
            if(request()->hasFile('production_file')) {
                $file = request()->file('production_file');
                Excel::import(new ProductionImport, $file);
            }
            return back()->with('success', 'Productia a fost actualizata cu success!');
        } catch (\Throwable $th) {
            return back()->with('failure', 'Actualizarea productiei nu a reusit.');
        }
    }

    /**
     * Import the updated production plan
     *
     * @return redirect
     */
    public function importProductionPlan()
    {
        try {
            if(request()->hasFile('production_plan')) {
                $file = request()->file('production_plan');
                Excel::import(new ProductionPlanImport, $file);
            }
            return back()->with('success', 'Planul de productie a fost actualizat cu success!');
        } catch (\Throwable $th) {
            return back()->with('failure', 'Actualizarea planului de productie nu a reusit.');
        }
    }

    /**
     * Import the deliveries
     *
     * @return redirect
     */
    public function importDeliveries()
    {
        try {
            if(request()->hasFile('deliveries')) {
                $file = request()->file('deliveries');
                Excel::import(new DeliveriesImport, $file);
            }
            return back()->with('success', 'Livrarile au fost actualizate cu success!');
        } catch (\Throwable $th) {
            return back()->with('failure', 'Actualizarea livrarilor nu a reusit.');
        }
    }
}
