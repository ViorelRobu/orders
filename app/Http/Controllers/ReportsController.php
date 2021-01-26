<?php

namespace App\Http\Controllers;

use App\DocArchive;
use App\Events\FinishedUpdatingProductionAndDeliveries;
use App\Exports\ActiveOrdersExport;
use App\Exports\DeliveriesDuringTimeRangeExport;
use App\Exports\ProductionPlanExport;
use App\Imports\DeliveriesImport;
use App\Imports\ProductionImport;
use App\Imports\ProductionPlanImport;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

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
        $now = Carbon::now()->format('d.m.Y His');
        $filename = 'comenzi active ' . $now . '.xlsx';
        Excel::store(new ActiveOrdersExport, $filename, 'exports');

        $this->archive('export', '/storage/exports/', $filename);

        return redirect('/storage/exports/' . $filename);

    }

    /**
     * Export the production plan
     *
     * @return void
     */
    public function exportProductionPlan()
    {
        $user = auth()->user()->name;
        $now = Carbon::now()->format('d.m.Y His');
        $filename = 'plan de productie' . $now . '.xlsx';
        Excel::store(new ProductionPlanExport, $filename, 'exports');

        $this->archive('export', '/storage/exports/', $filename);

        return redirect('/storage/exports/' . $filename);
    }

    /**
     * Export the production plan
     *
     * @return void
     */
    public function exportDeliveriesDuringTimeRange(Request $request)
    {
        $user = auth()->user()->name;
        $now = Carbon::now()->format('d.m.Y His');
        $filename = 'livrari pentru ' . $request->start . '-' . $request->end . ' exportat ' . $now . '.xlsx';
        Excel::store(new DeliveriesDuringTimeRangeExport($request->start, $request->end), $filename, 'exports');

        $this->archive('export', '/storage/exports/', $filename);

        return redirect('/storage/exports/' . $filename);
    }

    /**
     * Display the exports archive
     *
     * @return view
     */
    public function indexArchive()
    {
        return view('reports.exports');
    }

    /**
     * Display the imports archive
     *
     * @return view
     */
    public function indexImports()
    {
        return view('reports.imports');
    }

    /**
     * Fetch the archive for the exported documents
     *
     * @return void
     */
    public function fetchArchive()
    {
        $archive = DocArchive::all()->where('type', 'export');
        $archive->map(function($item, $index) {
            $user = User::find($item->user_id);
            $item->user_id = $user->name;
        });

        return DataTables::of($archive)
            ->addIndexColumn()
            ->addColumn('export', function ($archive) {
                return '<a href="' . $archive->link . '"><i class="fas fa-download"></i></a>';
            })
            ->addColumn('created', function ($archive) {
                return (Carbon::parse($archive->created_at))->format('d.m.Y H:i:s');
            })
            ->rawColumns(['export'])
            ->make(true);
    }

    /**
     * Fetch the archive for the exported documents
     *
     * @return void
     */
    public function fetchImports()
    {
        $archive = DocArchive::all()->where('type', 'import');
        $archive->map(function($item, $index) {
            $user = User::find($item->user_id);
            $item->user_id = $user->name;
        });

        return DataTables::of($archive)
            ->addIndexColumn()
            ->addColumn('export', function ($archive) {
                return '<a href="' . $archive->link . '"><i class="fas fa-download"></i></a>';
            })
            ->addColumn('created', function ($archive) {
                return (Carbon::parse($archive->created_at))->format('d.m.Y H:i:s');
            })
            ->rawColumns(['export'])
            ->make(true);
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

                $now = Carbon::now()->format('d.m.Y His');
                $filename = 'import productie ' . $now . '.xlsx';
                request()->file('production_file')->storeAs('/public/imports', $filename);

                $this->archive('import', '/storage/imports/', $filename);
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

                $now = Carbon::now()->format('d.m.Y Hms');
                $filename = 'import plan productie ' . $now . '.xlsx';
                request()->file('production_plan')->storeAs('/public/imports', $filename);

                $this->archive('import', '/storage/imports/', $filename);
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

                $now = Carbon::now()->format('d.m.Y Hms');
                $filename = 'import livrari ' . $now . '.xlsx';
                request()->file('deliveries')->storeAs('/public/imports', $filename);

                $this->archive('import', '/storage/imports/', $filename);
            }

            event(new FinishedUpdatingProductionAndDeliveries());

            return back()->with('success', 'Livrarile au fost actualizate cu success!');
        } catch (\Throwable $th) {
            return back()->with('failure', 'Actualizarea livrarilor nu a reusit.');
        }
    }

    /**
     * Save the upload/download to the archive
     *
     * @param string $type
     * @param string $path
     * @param string $filename
     * @return void
     */
    public function archive(string $type, string $path, string $filename)
    {
        $archive = new DocArchive();
        $archive->type = $type;
        $archive->link = $path . $filename;
        $archive->document = $filename;
        $archive->user_id = auth()->user()->id;
        $archive->save();
    }


}
