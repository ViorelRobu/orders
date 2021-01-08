<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DeliveriesDuringTimeRangeExport implements FromCollection, WithHeadings
{
    protected $timeframe = [];

    public function __construct($start, $end)
    {
        return $this->timeframe = [$start, $end];
    }
    /**
     * Headings for the excel table
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'Client', 'Volum livrat'
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $data = DB::table('order_details')
                    ->leftJoin('orders', 'order_details.order_id', '=', 'orders.id')
                    ->leftJoin('customers', 'orders.customer_id', '=', 'customers.id')
                    ->select('customers.name', DB::raw('SUM(order_details.volume)'))
                    ->groupBy('customers.name')
                    ->whereBetween('order_details.loading_date', $this->timeframe)
                    ->get();

        return $data;
    }
}
