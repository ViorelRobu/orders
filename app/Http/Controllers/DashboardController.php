<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Displays the dashboard to the user
     *
     * @return view
     */
    public function index()
    {
        $year = Carbon::now()->year;
        $start_of_month = Carbon::now()->startOfMonth()->weekOfYear;
        $end_of_month = Carbon::now()->endOfMonth()->weekOfYear;

        $weeks = [];

        if ($start_of_month == 53) {
            $start_of_month = 1;
            for ($i=$start_of_month; $i <= $end_of_month ; $i++) {
                $weeks[] = $i;
            }
        } else {
            for ($i = $start_of_month; $i <= $end_of_month; $i++) {
                $weeks[] = $i;
            }
        }

        $query = '';
        foreach ($weeks as $week) {
            $query .= 'CASE WHEN budget.week = ' . $week . ' THEN volume END AS budget_' . $week . ', CASE WHEN budget.week = ' . $week . ' THEN delivered END AS delivered_' . $week . ',';
        }

        $budget = DB::table('budget')
            ->where('budget.year', $year)
            ->whereIn('week', $weeks)
            ->leftJoin('product_types', 'product_types.id', '=', 'budget.product_type_id')
            ->select(['product_types.name', DB::raw(substr($query, 0, -1))])
            ->get();

        $collection = [];
        foreach ($budget as $data) {
            if (!array_key_exists($data->name, $collection)) {
                $collection[$data->name] = $data;
            } else {
                foreach ($data as $key => $value) {
                    if ($collection[$data->name]->$key == null) {
                        $collection[$data->name]->$key = $data->$key;
                    }
                }
            }
        }

        return view('dashboard', ['data' => $collection, 'weeks' => $weeks]);
    }
}
