<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BudgetExport implements FromCollection, WithHeadings
{
    protected $year;

    /**
     * Constructor
     *
     * @param int year
     */
    public function __construct($year)
    {
        return $this->year = $year;
    }

    /**
     * Headings for the excel table
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'Tip', 'An', 'Bugetat', 'Livrat'
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $data = DB::table('budget')
                    ->where('budget.year', $this->year)
                    ->leftJoin('product_types', 'product_types.id', '=', 'budget.product_type_id')
                    ->select(['product_types.name', 'budget.year', 'budget.volume', 'budget.delivered'])
                    ->get();

        return $data;
    }
}
