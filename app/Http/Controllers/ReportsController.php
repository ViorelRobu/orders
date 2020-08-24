<?php

namespace App\Http\Controllers;

use App\Exports\ActiveOrdersExport;
use App\Exports\ProductionPlanExport;
use App\Imports\ProductionImport;
use App\OrderDetail;
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
        return Excel::download(new ActiveOrdersExport, 'comenzi active.xlsx');
    }

    /**
     * Export the production plan
     *
     * @return void
     */
    public function exportProductionPlan()
    {
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
                // $data = Excel::toArray(new ProductionImport, $file);
                // unset($data[0][0]);
                // foreach ($data[0] as $row) {
                //     $detail = OrderDetail::find($row[0]);
                //     $detail->produced_ticom = $row[17];
                //     $detail->save();
                // }
            }
            return back()->with('success', 'Productia a fost actualizata cu success!');
        } catch (\Throwable $th) {
            return back()->with('failure', 'Actualizarea productiei nu a reusit.');
        }
    }
}
