<?php

namespace App\Listeners;

use App\Article;
use App\Budget;
use App\Events\FinishedUpdatingProductionAndDeliveries;
use App\ProductType;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class UpdateBudgetWithDeliveries implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(FinishedUpdatingProductionAndDeliveries $event)
    {
        $start = Carbon::now()->subMonth();
        $end = Carbon::now()->endOfYear();
        $data = DB::table('order_details')->whereNotNull('loading_date')->whereBetween('loading_date', [$start, $end])->get(['id', 'article_id', 'loading_date', 'volume']);

        $volumes = [];

        foreach ($data as $item) {
            $article = Article::find($item->article_id);
            $product = ProductType::find($article->product_type_id);
            $year = Carbon::parse($item->loading_date)->year;
            $week = Carbon::parse($item->loading_date)->weekOfYear;

            $budget = Budget::where('year', $year)->where('week', $week)->where('product_type_id', $product->id)->first();

            if (!is_null($budget)) {
                if (!array_key_exists($budget->id, $volumes)) {
                    $volumes[$budget->id] = $item->volume;
                } else {
                    $volumes[$budget->id] = $volumes[$budget->id] + $item->volume;
                }
            }
        }

        foreach ($volumes as $key => $value) {
            $budget = Budget::find($key);
            $budget->delivered = $value;
            $budget->save();
        }
    }
}
