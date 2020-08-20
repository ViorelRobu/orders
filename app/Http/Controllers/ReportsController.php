<?php

namespace App\Http\Controllers;

use App\Exports\ActiveOrdersExport;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    /**
     * Display to the user the all reports page
     *
     * @return void
     */
    public function index()
    {
        return view('reports.index');
    }

    public function exportActiveOrders()
    {
        $data = new ActiveOrdersExport();
        dd($data->collection());
    }
}
