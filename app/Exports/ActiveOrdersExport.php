<?php

namespace App\Exports;

use App\Order;
use App\Traits\RefinementsTranslator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ActiveOrdersExport implements FromCollection, WithHeadings
{
    use RefinementsTranslator;

    public function headings(): array
    {
        return [
            'id', 'Comanda interna', 'Comanda client', 'Auftrag', 'Client', 'Articol', 'Finisaje', 'Calitate', 'Grosime',
            'Latime', 'Lungime', 'Piese / inaltime', 'Nr randuri', 'Bucati', 'Volum necesar', 'Tip eticheta', 'Mod infoliere',
            'Pal', 'Volum produs', 'Saptamana productie', 'Livrat', 'Prioritatea', 'Specia'
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
                'order_details.foil as mod_infoliere',
                'order_details.produced_ticom as pal',
                'order_details.volume as vol_prod',
                'orders.production_kw as saptamana_productie',
                'order_details.loading_date as livrat',
                'orders.priority as prioritatea',
                'species.name as specie'
                ])
            ->whereIn('order_id', $active)
            ->whereNull('order_details.loading_date')
            ->get();

       foreach ($list as $item) {
            $item->finisaje = $this->translateForHumans($item->finisaje);
            $item->saptamana_productie ='KW ' . Carbon::parse($item->saptamana_productie)->weekOfYear;
            if ($item->livrat != null) {
                $item->livrat =Carbon::parse($item->livrat)->format('d.m.y');
            }
            if ($item->pal != 1) {
                $item->vol_prod = '0';
                $item->pal = '0';
            }
       }

       return $list;
    }
}
