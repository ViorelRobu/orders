<?php

namespace App\Http\Controllers;

use App\Article;
use App\Order;
use App\OrderDetail;
use App\Traits\RefinementsTranslator;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Refinement;
use Illuminate\Support\Facades\DB;

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
            $item->volume = round($item->volume, 3, PHP_ROUND_HALF_UP);
            $item->article = $article->name;
            if ($item->details_json != null) {
                $details_json = json_decode($item->details_json);
                foreach ($details_json as $key => $value) {
                    $item->$key = $value;
                }
            }
        });

        return DataTables::of($details)
            ->addIndexColumn()
            ->editColumn('refinements_list', function($details) {
                return $this->translateForHumans($details->refinements_list);
            })
            ->addColumn('actions', function ($details) {
                return view('orders.partials.actions_sub', ['detail' => $details]);
            })
            ->make(true);
    }

    /**
     * Store the order details into the database
     *
     * @param Order $order
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Order $order, Request $request)
    {
        $det = OrderDetail::where('order_id', $order->id)->latest()->first();
        if ($det == null) {
            $position = 1;
        } else {
            $position = $det->position + 1;
        }

        for ($i=0; $i < $request->pal_pcs; $i++) {
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
                $detail->volume = round(($this->pi * ($r**2) * $h * $request->pcs / 1000000000), 3, PHP_ROUND_HALF_UP);
            }
            $detail->position = $position;
            $detail->pcs_height = $request->pcs_height;
            $detail->rows = $request->rows;
            $detail->label = $request->label;
            $detail->foil = $request->foil;
            $detail->pal = $request->pal;
            $detail->details_json = $request->details_json;
            $detail->save();
        }

        return response()->json([
            'created' => true,
            'message' => 'Pozitia a fost adaugata in baza de date!',
            'type' => 'success'
        ], 201);
    }

    /**
     * Get all the details of a certain position
     *
     * @param Order $order
     * @param int $position
     * @return JsonResponse
     */
    public function getPosition(Order $order, $position)
    {
        $pos = DB::table('order_details')
            ->select('article_id', 'refinements_list', 'length', 'pcs', 'pcs_height', 'rows', 'label', 'foil', 'pal', 'details_json', DB::raw('count(*) as pallets'))
            ->where('order_id', $order->id)
            ->where('position', $position)
            ->groupBy(['article_id', 'refinements_list', 'length', 'pcs','pcs_height', 'rows', 'label', 'foil', 'pal','details_json'])
            ->get();

        $pos->map(function($item, $index) {
            $article = Article::find($item->article_id);
            $item->details = json_decode($item->details_json);
            $item->article = $article->name;
            $item->refinements_list = explode(',', $item->refinements_list);
        });

        return response()->json([
            'status' => 'success',
            'data' => $pos
        ]);
    }

    /**
     * Update all the packages from the position of a certain order
     *
     * @param Order $order
     * @param int $position
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Order $order, $position, Request $request)
    {
        try {
            $positions = OrderDetail::where('order_id', $order->id)->where('position', $position)->get();
            $article = Article::find($request->edit_article_id);
            foreach ($positions as $item) {
                $position = OrderDetail::find($item->id);
                $position->article_id = $article->id;
                $position->refinements_list = implode(',', $request->edit_refinements_list);
                $position->thickness = $article->thickness;
                $position->width = $article->width;
                $position->length = $request->edit_length;
                $position->pcs = $request->edit_pcs;
                if ($request->edit_length != null) {
                    $position->volume = ($article->thickness * $article->width * $request->edit_length * $request->edit_pcs) / 1000000000;
                } else {
                    $r = $article->width / 2;
                    $h = $article->thickness;
                    $position->volume = round(($this->pi * ($r ** 2) * $h * $request->edit_pcs / 1000000000), 3, PHP_ROUND_HALF_UP);
                }
                $position->pcs_height = $request->edit_pcs_height;
                $position->rows = $request->edit_rows;
                $position->label = $request->edit_label;
                $position->foil = $request->edit_foil;
                $position->pal = $request->edit_pal;
                $position->details_json = $request->edit_details_json;
                $position->save();
            }
            return response()->json([
                'updated' => true,
                'message' => 'Pozitiile au fost modificate cu succes!',
                'type' => 'success'
            ]);
        } catch(\Throwable $th) {
            return response()->json([
                'updated' => false,
                'message' => 'A aparut o eroare. Va rugam reincercati!',
                'type' => 'error',
                'error' => $th
            ]);
        }
    }

    /**
     * Copy a package n times
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function copy(Request $request)
    {
        try {
            $original = OrderDetail::find($request->id);

            for ($i=0; $i < $request->copies; $i++) {
                $copy = new OrderDetail();
                $copy->order_id = $original->order_id;
                $copy->article_id = $original->article_id;
                $copy->refinements_list = $original->refinements_list;
                $copy->thickness = $original->thickness;
                $copy->width = $original->width;
                $copy->length = $original->length;
                $copy->pcs = $original->pcs;
                $copy->volume = $original->volume;
                $copy->position = $original->position;
                $copy->pcs_height = $original->pcs_height;
                $copy->rows = $original->rows;
                $copy->label = $original->label;
                $copy->foil = $original->foil;
                $copy->pal = $original->pal;
                $copy->details_json = $original->details_json;
                $copy->save();
            }
            return response()->json([
                'copied' => true,
                'message' => 'Pozitia a fost copiata de ' . $request->copies . ' ori!',
                'type' => 'success'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'copied' => false,
                'message' => 'A aparut o eroare. Va rugam reincercati!',
                'type' => 'error',
                'error' => $th
            ]);
        }
    }

    /**
     * Deletes all the packages on one position from the database
     *
     * @param Order $order
     * @param int $position
     * @return JsonResponse
     */
    public function destroyPosition(Order $order, $position)
    {
        try {
            $positions = OrderDetail::where('order_id', $order->id)->where('position', $position)->get();
            foreach ($positions as $position) {
                $position->delete();
            }

            return response()->json([
                'deleted' => true,
                'message' => 'Toate pachete de pe aceasta pozitie au fost sterse.',
                'type' => 'success'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'deleted' => false,
                'message' => 'A aparut o eroare. Va rugam reincercati!',
                'type' => 'error',
                'error' => $th
            ]);
        }
    }

    /**
     * Delete one package from the database
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function destroyPackage(Request $request)
    {
        try {
            $package = OrderDetail::find($request->id);
            $package->delete();

            return response()->json([
                'deleted' => true,
                'message' => 'Pachet sters!',
                'type' => 'success'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'deleted' => false,
                'message' => 'A aparut o eroare. Va rugam reincercati!',
                'type' => 'error',
                'error' => $th
            ]);
        }
    }
}
