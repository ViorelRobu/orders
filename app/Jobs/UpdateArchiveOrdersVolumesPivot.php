<?php

namespace App\Jobs;

use App\ArchivedOrderVolume;
use App\Order;
use App\OrderDetail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateArchiveOrdersVolumesPivot implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $orders = Order::where('archived', '=', 1)->get();

        foreach($orders as $order) {
            $existing = ArchivedOrderVolume::where('order_id', $order->id)->get();

            $order_total = round(OrderDetail::where('order_id', $order->id)->sum('volume'), 3, PHP_ROUND_HALF_UP);
            $order_delivered = round(OrderDetail::where('order_id', $order->id)->whereNotNull('loading_date')->sum('volume'), 3, PHP_ROUND_HALF_UP);

            if ($existing->count() == 0) {
                $pivot = new ArchivedOrderVolume();
                $pivot->order_id = $order->id;
                $pivot->order_volume = $order_total;
                $pivot->delivered_volume = $order_delivered;
                $pivot->save();
            } else {
                $existing[0]->order_volume = $order_total;
                $existing[0]->delivered_volume = $order_delivered;
                $existing[0]->save();
            }
        }
    }
}
