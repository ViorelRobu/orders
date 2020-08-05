<?php

namespace App\Http\Controllers;

use App\Country;
use App\Customer;
use App\Destination;
use App\Order;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class OrdersController extends Controller
{
    protected $rules = [
        'customer_id' => 'required',
        'customer_order' => 'sometimes',
        'auftrag' => 'sometimes',
        'destination_id' => 'required',
        'customer_kw' => 'sometimes',
        'production_kw' => 'required',
        'delivery_kw' => 'required',
        'eta' => 'required',
        'observations' => 'sometimes',
    ];

    /**
     * Show the all orders page
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $customers = Customer::all();
        $countries = Country::all();

        return view('orders.index', [
            'customers' => $customers,
            'countries' => $countries,
        ]);
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
            Carbon::setLocale('ro');
            $customer = Customer::find($item->customer_id);
            $destination = Destination::find($item->destination_id);
            $country = Country::find($destination->country_id);
            $item->month = strtoupper((Carbon::parse($item->delivery_kw))->monthName);
            $item->customer = $customer->name;
            $item->destination = $destination->address . ', ' . $country->name;
            $item->kw_customer = 'KW ' . (Carbon::parse($item->customer_kw))->weekOfYear;
            $item->kw_production = 'KW ' . (Carbon::parse($item->production_kw))->weekOfYear;
            $item->kw_delivery = 'KW ' . (Carbon::parse($item->delivery_kw))->weekOfYear;
            $item->eta = 'KW ' . (Carbon::parse($item->eta))->weekOfYear;
            $item->date_loading = (Carbon::parse($item->loading_date))->format('d.m.Y');
            $item->date_loading = (Carbon::parse($item->loading_date))->format('d.m.Y');
            $item->total = 50;
            $item->produced = 50;
            $item->to_produce = 0;
            $item->delivered = 50;
            $item->to_deliver = 0;
            $item->ready_to_deliver = 0;
            $item->percentage = 0.954822;
            $item->percentageDisplay = round(($item->percentage * 100),2) . '%';

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
        $order->customer_kw = (Carbon::parse($order->customer_kw))->format('d.m.Y');
        $order->production_kw = (Carbon::parse($order->production_kw))->format('d.m.Y');
        $order->delivery_kw = (Carbon::parse($order->delivery_kw))->format('d.m.Y');
        $order->eta = (Carbon::parse($order->eta))->format('d.m.Y');
        $order->customer = $customer->name;
        $order->address = $destination->address;
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

        $latest_order = DB::table('orders')->latest()->first();
        if ($latest_order) {
            $lt_order = Carbon::parse($latest_order->created_at);
        } else {
            $lt_order = (Carbon::now())->subDay();
        }
        $latest_number = DB::table('order_numbers')->latest()->first();
        if ($latest_number) {
            $lt_number = Carbon::parse($latest_number->created_at);
        } else {
            return back()->with(['errors' => 'Trebuie setat primul numar de comanda!']);
        }

        if ($validator->passes()) {
            if ($lt_number > $lt_order) {
                $order->order = $latest_number->start_number;
            } else {
                $order->order = $latest_order->order + 1;
            }

            $order->customer_id = $validator->valid()['customer_id'];
            $order->customer_order = $validator->valid()['customer_order'];
            $order->auftrag = $validator->valid()['auftrag'];
            $order->destination_id = $validator->valid()['destination_id'];
            $order->customer_kw = $validator->valid()['customer_kw'];
            $order->production_kw = $validator->valid()['production_kw'];
            $order->delivery_kw = $validator->valid()['delivery_kw'];
            $order->eta = $validator->valid()['eta'];
            $order->save();

            return redirect('/orders/' . $order->id . '/show');
        }

        return back()->with(['errors' => $validator->errors()]);
    }

    public function show(Order $order)
    {
        $customer = Customer::find($order->customer_id);
        $destination = Destination::find($order->destination_id);
        $country = Country::find($destination->country_id);

        $customer_kw = (Carbon::parse($order->customer_kw))->weekOfYear;
        $production_kw = (Carbon::parse($order->production_kw))->weekOfYear;
        $delivery_kw = (Carbon::parse($order->delivery_kw))->weekOfYear;
        if ($order->eta == null) {
            $eta = 'nespecificat';
        } else {
            $eta = (Carbon::parse($order->eta))->weekOfYear;
        }
        $loading_date = (Carbon::parse($order->loading_date))->format('d.m.Y');

        return view('orders.show', [
            'order' => $order,
            'customer' => $customer,
            'destination' => $destination,
            'country' => $country,
            'customer_kw' => $customer_kw,
            'production_kw' => $production_kw,
            'delivery_kw' => $delivery_kw,
            'loading_date' => $loading_date,
            'eta' => $eta,
        ]);
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
            $order->customer_id = $validator->valid()['customer_id'];
            $order->customer_order = $validator->valid()['customer_order'];
            $order->auftrag = $validator->valid()['auftrag'];
            $order->destination_id = $validator->valid()['destination_id'];
            $order->customer_kw = $validator->valid()['customer_kw'];
            $order->production_kw = $validator->valid()['production_kw'];
            $order->delivery_kw = $validator->valid()['delivery_kw'];
            $order->eta = $validator->valid()['eta'];
            $order->save();

            return response()->json([
                'updated' => true,
                'message' => 'Intrare editata in baza de date!',
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
