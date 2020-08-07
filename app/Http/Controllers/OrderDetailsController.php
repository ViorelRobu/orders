<?php

namespace App\Http\Controllers;

use App\Article;
use App\Order;
use App\OrderDetail;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class OrderDetailsController extends Controller
{
    public function fetch(Order $order)
    {
        $details = OrderDetail::where('order_id', $order->id)->get();
        $details->map(function($item, $index) {
            $article = Article::find($item->article_id);
            $item->article = $article->name;
        });

        return DataTables::of($details)
            ->addIndexColumn()
            ->addColumn('actions', function ($customers) {
                // return view('customers.partials.actions', ['customer' => $customers]);
            })
            ->make(true);
    }
}
