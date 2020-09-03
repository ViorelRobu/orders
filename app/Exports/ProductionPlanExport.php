<?php

namespace App\Exports;

use App\Order;
use App\Traits\RefinementsTranslator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductionPlanExport implements FromCollection, WithHeadings
{
    use RefinementsTranslator;

    /**
     * Headings for the excel table
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'id', 'Comanda', 'nr. comanda client', 'Auftrag', 'Client', 'Articol TiCom', 'Finisaje', 'Calitate',
            'Gros[mm]', 'Lat[mm]', 'Lung[mm]', 'Piese/inaltime', 'Nr randuri', 'Buc/palet', 'Vol[m3]', 'Tip eticheta',
            'Produs', 'Lot', 'Specia', 'Saptamana de productie'
        ];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $active = Order::whereNull('loading_date')->pluck('id');
        $list = DB::table('order_details')
        ->join('orders', 'order_details.order_id', '=', 'orders.id')
        ->join('customers', 'orders.customer_id', '=', 'customers.id')
        ->join('articles', 'order_details.article_id', '=', 'articles.id')
        ->join('quality', 'articles.quality_id', '=', 'quality.id')
        ->join('species', 'articles.species_id', '=', 'species.id')
        ->select([
            'order_details.id as id',
            'orders.order as comanda_interna',
            'orders.customer_order as comanda_client',
            'orders.auftrag as auftrag',
            'customers.name as client',
            'articles.name as articol',
            'order_details.refinements_list as finisaje',
            'quality.name as calitate',
            'order_details.thickness as grosime',
            'order_details.width as latime',
            'order_details.length as lungime',
            'order_details.pcs_height as piese_inaltime',
            'order_details.rows as randuri',
            'order_details.pcs as bucati',
            'order_details.volume as volum_necesar',
            'order_details.label as tip_eticheta',
            'order_details.produced_batch as produs',
            'order_details.batch as lot',
            'species.name as specie',
            'orders.production_kw as saptamana_productie',
        ])
            ->orderBy('orders.order', 'asc')
            ->orderBy('quality.name', 'asc')
            ->orderBy('species.name', 'asc')
            ->orderBy('order_details.thickness', 'asc')
            ->orderBy('order_details.width', 'asc')
            ->orderBy('order_details.length', 'asc')
            ->whereIn('order_id', $active)
            ->get();

        foreach ($list as $item) {
            $item->finisaje = $this->translateForHumans($item->finisaje);
            $item->saptamana_productie = 'KW ' . Carbon::parse($item->saptamana_productie)->weekOfYear;
            if($item->produs != 1) {
                $item->produs = '0';
            }
        }

        return $list;
    }
}
