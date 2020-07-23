<?php

namespace App\Http\Controllers;

use App\Country;
use App\Customer;
use App\Destination;
use App\Order;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class OrdersController extends Controller
{
    protected $rules = [
        // 'name' => 'required',
    ];

    /**
     * Show the all orders page
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('orders.index');
    }

    /**
     * Fetch all the active orders from the database
     *
     * @return mixed
     * @throws Exception
     */
    public function fetchAll()
    {
        $orders = Order::all();

        $orders->map(function ($item, $index) {
            $customer = Customer::find($item->customer_id);
            $destination = Destination::find($item->destination_id);
            $country = Country::find($destination->country_id);
            $item->customer = $customer->name;
            $item->destination = $destination->address . ', ' . $country->name;
            $item->kw_customer = 'KW ' . (Carbon::parse($item->customer_kw))->weekOfYear;
            $item->kw_production = 'KW ' . (Carbon::parse($item->production_kw))->weekOfYear;
            $item->kw_delivery = 'KW ' . (Carbon::parse($item->delivery_kw))->weekOfYear;
            $item->date_loading = (Carbon::parse($item->loading_date))->format('d.m.Y');
            $item->date_loading = (Carbon::parse($item->loading_date))->format('d.m.Y');
            $item->total = 50;
            $item->produced = 50;
            $item->to_produce = 0;
            $item->delivered = 50;
            $item->to_deliver = 0;
            $item->ready_to_deliver = 0;
            $item->percentage = '100%';

        });

        return DataTables::of($orders)
            ->addColumn('actions', function ($orders) {
                return view('orders.partials.actions', ['order' => $orders]);
            })
            ->make(true);
    }

    /**
     * Get the details for a single order
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function fetch(Request $request)
    {
        $order = Order::find($request->id);
        $customer = Customer::find($order->customer_id);
        $destination = Destination::find($order->destination_id);
        $country = Country::find($destination->country_id);
        $order->customer = $customer->name;
        $order->destination = $destination->address;
        $order->country = $country->name;

        return (new JsonResponse(['message' => 'success', 'message_type' => 'success', 'data' => $order]));
    }

    /**
     * Persist the order in the database
     *
     * @param Order $order
     * @param Request $request
     * @return redirect
     */
    public function store(Order $order, Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->passes()) {
            $order->order = $validator->valid()['order'];
            $order->customer_id = $validator->valid()['customer_id'];
            $order->customer_order = $validator->valid()['customer_order'];
            $order->auftrag = $validator->valid()['auftrag'];
            $order->destination_id = $validator->valid()['destination_id'];
            $order->customer_kw = $validator->valid()['customer_kw'];
            $order->production_kw = $validator->valid()['production_kw'];
            $order->delivery_kw = $validator->valid()['delivery_kw'];
            $order->month = $validator->valid()['month'];
            $order->loading_date = $validator->valid()['loading_date'];
            $order->priority = $validator->valid()['priority'];
            $order->observations = $validator->valid()['observations'];
            $order->save();

            return redirect('/orders/' . $order->id . '/show');
        }

        return back()->with(['error' => $validator->errors()]);
    }

    /**
     * Update a certain order inside the database
     *
     * @param Order $order
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Order $order, Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->passes()) {
            $order->order = $validator->valid()['order'];
            $order->customer_id = $validator->valid()['customer_id'];
            $order->customer_order = $validator->valid()['customer_order'];
            $order->auftrag = $validator->valid()['auftrag'];
            $order->destination_id = $validator->valid()['destination_id'];
            $order->customer_kw = $validator->valid()['customer_kw'];
            $order->production_kw = $validator->valid()['production_kw'];
            $order->delivery_kw = $validator->valid()['delivery_kw'];
            $order->month = $validator->valid()['month'];
            $order->loading_date = $validator->valid()['loading_date'];
            $order->priority = $validator->valid()['priority'];
            $order->observations = $validator->valid()['observations'];
            $order->save();

            return response()->json([
                'updated' => true,
                'message' => 'Comanda adaugata in baza de date!',
                'type' => 'success'
            ]);
        }

        return response()->json([
            'updated' => false,
            'message' => 'A aparut o eroare. Verificati daca ati completat corect toate datele cerute.',
            'type' => 'error'
        ]);
    }
}
