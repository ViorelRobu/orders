<?php

namespace App\Http\Controllers;

use App\Article;
use App\Order;
use App\OrderDetail;
use App\Traits\RefinementsTranslator;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Refinement;

class OrderDetailsController extends Controller
{
    use RefinementsTranslator;

    protected $pi = 3.142;

    /**
     * Fetch the order details of a certain order
     *
     * @param Order $order
     * @return Datatables
     */
    public function fetch(Order $order)
    {
        $details = OrderDetail::where('order_id', $order->id)->get();
        $details->map(function($item, $index) {
            $article = Article::find($item->article_id);
            $item->article = $article->name;
        });

        return DataTables::of($details)
            ->addIndexColumn()
            ->editColumn('refinements_list', function($details) {
                return $this->translateForHumans($details->refinements_list);
            })
            ->addColumn('actions', function ($customers) {
                // return view('customers.partials.actions', ['customer' => $customers]);
            })
            ->make(true);
    }

    public function store(Order $order, Request $request)
    {

        for ($i=0; $i < $request->pal; $i++) {
            $article = Article::find($request->article_id);
            $detail = new OrderDetail();
            $detail->order_id = $order->id;
            $detail->article_id = $request->article_id;
            $detail->refinements_list = implode(',',$request->refinements_list);
            $detail->thickness = $article->thickness;
            $detail->width = $article->width;
            $detail->length = $request->length;
            $detail->pcs = $request->pcs;
            if ($request->length != null) {
                $detail->volume = ($article->thickness * $article->width * $request->length * $request->pcs) / 1000000000;
            } else {
                $r = $article->width / 2;
                $h = $article->thickness;
                $detail->volume = round(($this->pi * ($r**2) * $h* $request->pcs / 1000000000), 3, PHP_ROUND_HALF_UP);
            }
            $detail->details_json = 'test';
            $detail->save();
        }

        return response()->json([
            'created' => true,
            'message' => 'Pozitia a fost adaugata in baza de date!',
            'type' => 'success'
        ], 201);
    }
}
