<?php

namespace App\Http\Controllers;

use App\Exports\ActiveOrdersExport;
use Illuminate\Http\Request;
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
}
