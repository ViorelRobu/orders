<?php

namespace App\Http\Controllers;

use App\Article;
use App\Country;
use App\Customer;
use App\Destination;
use App\Order;
use App\OrderDetail;
use App\Refinement;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Laravel\Ui\Presets\React;
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
     * Show the order archive page
     *
     * @return Application|Factory|View
     */
    public function archive()
    {
        return view('orders.archive');
    }

    /**
     * Fetch all the active orders from the database
     *
     * @return mixed
     * @throws Exception
     */
    public function fetchAll()
    {
        $orders = Order::where('archived', '=', 0)->get();

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
     * Fetch all the archived orders from the database
     *
     * @return mixed
     * @throws Exception
     */
    public function fetchAllArchive()
    {
        $orders = Order::where('archived', '=', 1)->get();

        $orders->map(function ($item, $index) {
            $customer = Customer::find($item->customer_id);
            $destination = Destination::find($item->destination_id);
            $country = Country::find($destination->country_id);
            $item->customer = $customer->name;
            $item->destination = $destination->address . ', ' . $country->name;
            $item->loading_date = (Carbon::parse($item->loading_date))->format('d.m.Y');
            $item->total = 50;
        });

        return DataTables::of($orders)
            ->addColumn('actions', function ($orders) {
                return view('orders.partials.archive', ['order' => $orders]);
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
        $order->country_id = $country->id;

        return (new JsonResponse(['message' => 'success', 'message_type' => 'success', 'data' => $order]));
    }

    /**
     * Update the priority of the order
     *
     * @param Order $order
     * @param Request $request
     * @return JsonResponse
     */
    public function setPriority(Order $order, Request $request)
    {
        $order->priority = $request->priority;
        $order->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Prioritatea a fost setata cu success!',
            'value' => $request->priority
        ]);
    }

    /**
     * Update the main order details
     *
     * @param Order $order
     * @param Request $request
     * @return JsonResponse
     */
    public function setDetails(Order $order, Request $request)
    {
        $order->customer_id = $request->customer_id;
        $order->customer_order = $request->customer_order;
        $order->auftrag = $request->auftrag;
        $order->destination_id = $request->destination_id;
        $order->save();

        $customer = Customer::find($order->customer_id);
        $destination = Destination::find($order->destination_id);
        $country = Country::find($destination->country_id);

        return response()->json([
            'status' => 'success',
            'message' => 'Detaliile comenzii au fost actualizate cu success!',
            'order' => $order,
            'customer' => $customer,
            'country' => $country,
            'destination' => $destination
        ]);
    }

    /**
     * Update the order observations
     *
     * @param Order $order
     * @param Request $request
     * @return JsonResponse
     */
    public function setObservations(Order $order, Request $request)
    {
        $order->observations = $request->observations;
        $order->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Observatiile au fost actualizate cu success!',
            'order' => $order,
        ]);
    }

    /**
     * Set the loading date and archive the order
     *
     * @param Order $order
     * @param Request $request
     * @return JsonResponse
     */
    public function ship(Order $order, Request $request)
    {
        $order->loading_date = (Carbon::parse($request->loading_date))->toDateString();
        $order->archived = 1;
        $order->save();

        $date = (Carbon::parse($request->loading_date))->format('d.m.y');

        return back();
    }

    /**
     * Update the planning dates
     *
     * @param Order $order
     * @param Request $request
     * @return JsonResponse
     */
    public function setDates(Order $order, Request $request)
    {
        Carbon::setLocale('ro');
        $order->customer_kw = $request->customer_kw;
        $order->production_kw = $request->production_kw;
        $order->delivery_kw = $request->delivery_kw;
        $order->eta = $request->eta;
        $order->save();

        $order->customer_kw_text = (Carbon::parse($order->customer_kw))->weekOfYear;
        $order->production_kw_text = (Carbon::parse($order->production_kw))->weekOfYear;
        $order->delivery_kw_text = (Carbon::parse($order->delivery_kw))->weekOfYear;
        if ($order->eta != null) {
            $order->eta_text = (Carbon::parse($order->eta))->weekOfYear;
        } else {
            $order->eta_text = 'nespecificat';
        }
        $order->month = strtoupper((Carbon::parse($order->delivery_kw))->monthName);

        return response()->json([
            'status' => 'success',
            'message' => 'Datele au fost modificate cu success!',
            'order' => $order
        ]);
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
            $order->customer_kw = (Carbon::parse($validator->valid()['customer_kw']))->toDateString();
            $order->production_kw = (Carbon::parse($validator->valid()['production_kw']))->toDateString();
            $order->delivery_kw = (Carbon::parse($validator->valid()['delivery_kw']))->toDateString();
            $order->eta = (Carbon::parse($validator->valid()['eta']))->toDateString();
            $order->save();

            return redirect('/orders/' . $order->id . '/show');
        }

        return back()->with(['errors' => $validator->errors()]);
    }

    /**
     * Display to the user the order page
     *
     * @param Order $order
     * @return view
     */
    public function show(Order $order)
    {
        Carbon::setLocale('ro');
        $customer = Customer::find($order->customer_id);
        $destination = Destination::find($order->destination_id);
        $country = Country::find($destination->country_id);
        $order->month = strtoupper((Carbon::parse($order->delivery_kw))->monthName);
        $order->archived_text = $order->archived === 1 ? ' - Arhivata' : '';

        $customer_kw = (Carbon::parse($order->customer_kw))->weekOfYear;
        $production_kw = (Carbon::parse($order->production_kw))->weekOfYear;
        $delivery_kw = (Carbon::parse($order->delivery_kw))->weekOfYear;
        if ($order->eta == null) {
            $eta = 'nespecificat';
        } else {
            $eta = (Carbon::parse($order->eta))->weekOfYear;
        }
        $loading_date = (Carbon::parse($order->loading_date))->format('d.m.Y');
        $customers = Customer::all();
        $countries = Country::all();
        $articles = Article::all();
        $refinements = Refinement::all();
        $fields = explode('|', $order->details_fields);
        $order_total = OrderDetail::where('order_id', $order->id)->sum('volume');
        $rest_to_produce = OrderDetail::where('order_id', $order->id)->where('produced_ticom', 0)->sum('volume');
        $delivered = OrderDetail::where('order_id', $order->id)->whereNotNull('loading_date')->sum('volume');
        $ready_for_delivery = OrderDetail::where('order_id', $order->id)->where('produced_ticom', 1)->whereNull('loading_date')->sum('volume');
        if ($order_total == $rest_to_produce) {
            $finished = 0;
        } else {
            $finished = round(((($order_total - $rest_to_produce) / $order_total) * 100), 2, PHP_ROUND_HALF_UP);
        }
        if ($delivered > 0) {
            $percentage_delivered = round(($delivered / $order_total) * 100, 2, PHP_ROUND_HALF_UP);
        } else {
            $percentage_delivered = 0;
        }

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
            'customers' => $customers,
            'countries' => $countries,
            'articles' => $articles,
            'refinements' => $refinements,
            'fields' => $fields,
            'order_total' => $order_total,
            'rest_to_produce' => $rest_to_produce,
            'delivered' => $delivered,
            'ready_for_delivery' => $ready_for_delivery,
            'finished' => $finished,
            'percentage_delivered' => $percentage_delivered,
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
                'type' => 'success',
                'data' => $request->all()
            ]);
        }

        return response()->json([
            'updated' => false,
            'message' => 'A aparut o eroare. Verificati daca ati completat corect toate datele cerute.',
            'type' => 'error'
        ]);
    }

    /**
     * Adds or updates the details fields
     *
     * @param Order $order
     * @param Request $request
     * @return JsonResponse
     */
    public function fields(Order $order, Request $request)
    {
        if ($order->details_fields == null) {
            $order->details_fields = $request->details_fields;
            $order->save();
        } else {
            $order->details_fields = rtrim($order->details_fields, '|') . '|' . rtrim($request->details_fields, '|');
            $order->save();

            $fields_arr = explode('|', $order->details_fields);
            $details = OrderDetail::where('order_id',$order->id)->get();
            foreach ($fields_arr as $field) {
                foreach ($details as $detail) {
                    $data = json_decode($detail->details_json);
                    foreach ($data as $new_detail) {
                        if (!isset($new_detail->$field)) {
                            $data->$field = '';
                        }
                    }
                    $new_json_details = OrderDetail::find($detail->id);
                    $new_json_details->details_json = json_encode($data);
                    $new_json_details->save();
                }
            }

        }

        return response()->json([
            'success' => true,
            'message' => 'Campuri adaugate/modificate cu succes!',
            'type' => 'success',
            'data' => $order->details_fields
        ]);
    }
}
