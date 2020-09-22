<?php

namespace App\Http\Controllers;

use App\Article;
use App\Country;
use App\Customer;
use App\Destination;
use App\Order;
use App\OrderAttachment;
use App\OrderDetail;
use App\Refinement;
use App\Traits\GetAudits;
use App\Traits\RefinementsTranslator;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Laravel\Ui\Presets\React;
use Yajra\DataTables\Facades\DataTables;
use PDF;

class OrdersController extends Controller
{
    use RefinementsTranslator;
    use GetAudits;

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

    protected $messages = [
        'customer_id.required' => 'Selectati un furnizor!',
        'destination_id.required' => 'Introduceti o adresa de livrare!',
        'production_kw.required' => 'Selectati saptamana de productie!',
        'delivery_kw.required' => 'Selectati saptamana de incarcare!',
        'eta.required' => 'Selectati ETA!',
    ];

    protected $dictionary = [
        'customer_id' => [
            'new_name' => 'client',
            'model' => 'App\Customer',
            'property' => 'name'
        ],
        'destination_id' => [
            'new_name' => 'adresa de livrare',
            'model' => 'App\Destination',
            'property' => 'address'
        ],
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
            $articles = DB::table('order_details')
                    ->select('article_id')
                    ->where('order_id', $item->id)
                    ->groupBy('article_id')
                    ->pluck('article_id');
            $specification_ids = DB::table('articles')
                    ->select('product_type_id')
                    ->whereIn('id', $articles)
                    ->groupBy('product_type_id')
                    ->pluck('product_type_id');
            $specifications = DB::table('product_types')->select('name')->whereIn('id', $specification_ids)->pluck('name')->toArray();
            $item->specification = implode(',', $specifications);
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
            $order_total = round(OrderDetail::where('order_id', $item->id)->sum('volume'), 3, PHP_ROUND_HALF_UP);
            $item->total = $order_total;
            $order_produced = round(OrderDetail::where('order_id', $item->id)
                    ->where('produced_ticom', 1)
                    ->sum('volume'), 3, PHP_ROUND_HALF_UP);
            $item->produced = $order_produced;
            $item->to_produce = round($order_total - $order_produced, 3, PHP_ROUND_HALF_UP);
            $order_delivered = round(OrderDetail::where('order_id', $item->id)
                    ->where('loading_date', '!=', null)
                    ->sum('volume'), 3, PHP_ROUND_HALF_UP);
            $item->delivered = $order_delivered;
            $order_ready_to_deliver = round(OrderDetail::where('order_id', $item->id)
            ->where('loading_date', '=', null)
            ->where('produced_ticom', 1)
            ->sum('volume'), 3, PHP_ROUND_HALF_UP);
            $item->ready_to_deliver = $order_ready_to_deliver;
            $item->to_deliver = round($order_total - $order_delivered, 3, PHP_ROUND_HALF_UP);
            if ($order_total == 0 && $order_produced == 0) {
                $item->percentage = 0;
            } else {
                $item->percentage = round($order_produced / $order_total, 3, PHP_ROUND_HALF_UP);
            }
            $item->percentageDisplay = round(($item->percentage * 100),2) . '%';

        });

        return DataTables::of($orders)
            ->addColumn('show', function ($orders) {
                return '<a href="/orders/' .  $orders->id . '/show" class="show" target="_blank"><i class="fas fa-eye"></i></a>';
            })
            ->addColumn('actions', function ($orders) {
                return view('orders.partials.actions', ['order' => $orders]);
            })
            ->rawColumns(['show'])
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
            $articles = DB::table('order_details')
            ->select('article_id')
                ->where('order_id', $item->id)
                ->groupBy('article_id')
                ->pluck('article_id');
            $specification_ids = DB::table('articles')
                ->select('product_type_id')
                ->whereIn('id', $articles)
                ->groupBy('product_type_id')
                ->pluck('product_type_id');
            $specifications = DB::table('product_types')->select('name')->whereIn('id', $specification_ids)->pluck('name')->toArray();
            $item->specification = implode(',', $specifications);
            $customer = Customer::find($item->customer_id);
            $destination = Destination::find($item->destination_id);
            $country = Country::find($destination->country_id);
            $item->customer = $customer->name;
            $item->destination = $destination->address . ', ' . $country->name;
            $item->loading_date = (Carbon::parse($item->loading_date))->format('d.m.Y');
            $order_total = round(OrderDetail::where('order_id', $item->id)->sum('volume'), 3, PHP_ROUND_HALF_UP);
            $item->total = $order_total;
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
        $validator = Validator::make($request->all(), ['priority' => 'required'], ['priority.required' => 'Trebuie sa introduceti o prioritate!']);

        if ($validator->passes()) {
            $order->priority = $request->priority;
            $order->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Prioritatea a fost setata cu success!',
                'value' => $request->priority
            ]);
        } else {
            return response()->json([
                'status' => 'failure',
                'message' => $validator->errors(),
            ], 406);
        }
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
        $validator = Validator::make($request->all(),
            [
                'customer_id' => 'required',
                'customer_order' => 'sometimes',
                'auftrag' => 'required',
                'destination_id' => 'required',
            ],
            [
                'customer_id.required' => 'Selectati un client!',
                'auftrag.required' => 'Introduceti numarul de auftrag!',
                'destination_id.required' => 'Introduceti locul de livrare!',
            ]);

        if ($validator->passes()) {
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
        } else {
            return response()->json([
                'status' => 'failure',
                'message' => $validator->errors(),
            ], 406);
        }
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
        $validator = Validator::make($request->all(), ['loading_date' => 'required'], ['loading_date.required' => 'Introduceti o data de livrare!']);

        if ($validator->passes()) {
            $order->loading_date = (Carbon::parse($request->loading_date))->toDateString();
            $order->archived = 1;
            $order->save();

            // insert the loading dates for the package that have no loading date
            $details = OrderDetail::where('order_id', $order->id)->get();
            foreach ($details as $detail) {
                $detail->loading_date = (Carbon::parse($request->loading_date))->toDateString();
                $detail->produced_ticom = 1;
                $detail->save();
            }

            $date = (Carbon::parse($request->loading_date))->format('d.m.y');

            return back();
        } else {
            return back()->with(['errors' => $validator->errors()]);
        }
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
        $validator = Validator::make(
            $request->all(),
            [
                'customer_kw' => 'sometimes',
                'production_kw' => 'required',
                'delivery_kw' => 'required',
                'eta' => 'sometimes',
            ],
            [
                'production_kw.required' => 'Selectati o saptamana de productie!',
                'delivery_kw.required' => 'Selectati saptamana de livrare!',
            ]
        );

        if ($validator->passes()) {
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
        } else {
            return response()->json([
                'status' => 'failure',
                'message' => $validator->errors()
            ], 406);
        }
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
        $validator = Validator::make($request->all(), $this->rules, $this->messages);

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

            $order->customer_id = $request->customer_id;
            $order->customer_order = $request->customer_order;
            $order->auftrag = $request->auftrag;
            $order->destination_id = $request->destination_id;
            $order->customer_kw = (Carbon::parse($request->customer_kw))->toDateString();
            $order->production_kw = (Carbon::parse($request->production_kw))->toDateString();
            $order->delivery_kw = (Carbon::parse($request->delivery_kw))->toDateString();
            $order->eta = (Carbon::parse($request->eta))->toDateString();
            $order->save();

            return redirect('/orders/' . $order->id . '/show');
        }

        return back()->with(['errors' => $validator->errors()]);
    }

    /**
     * Print the order as PDF
     *
     * @param Order $order
     * @return void
     */
    public function print(Order $order, $orientation)
    {
        $document = 'comanda ' . $order->order . '.pdf';
        $details = OrderDetail::where('order_id', $order->id)->get();
        $total = OrderDetail::where('order_id', $order->id)->sum('volume');
        $fields = [];
        if ($order->details_fields != null) {
            foreach(explode('|', $order->details_fields) as $field) {
                $fields[] = $field;
            }
        }

        $details->map(function($item, $index) {
            $item->refinements_list = $this->translateForHumans($item->refinements_list);
            $item->index = $index+1;
            if($item->details_json != '{}') {
                foreach(json_decode($item->details_json) as $key => $value) {
                    $item[$key] = $value;
                }
            }
        });

        return PDF::loadHTML(view('print.' . $orientation, [
                            'order' => $order,
                            'details' => $details,
                            'fields' => $fields,
                            'total' => $total,
                        ]))
                    ->setPaper('A4', $orientation)
                    ->stream($document);
    }

    /**
     * Print the multiple orders as a single PDF
     *
     * @param Request $request
     * @return void
     */
    public function printMultiple(Request $request)
    {
        $start = Order::where('order', $request->start)->pluck('id');
        $end = Order::where('order', $request->end)->pluck('id');
        $orders = Order::whereBetween('id', [$start[0], $end[0]])->where('archived', 0)->get();

        $document = 'comenzi multiple.pdf';

        $orders->map(function($item, $index) {
            $fields = [];
            if ($item->details_fields != null) {
                foreach(explode('|', $item->details_fields) as $field) {
                    $fields[] = $field;
                }
            }
            $total = OrderDetail::where('order_id', $item->id)->sum('volume');
            $item->details_fields = $fields;
            $item->total = $total;
        });

        return PDF::loadHTML(view('print.multiple.' . $request->orientation, [
                            'orders' => $orders,
                        ]))
                    ->setPaper('A4', $request->orientation)
                    ->stream($document);
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
        if ($order->details_fields != null) {
            $fields = explode('|', $order->details_fields);
        } else {
            $fields = [];
        }
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
        $validator = Validator::make($request->all(), $this->rules, $this->messages);

        if ($validator->passes()) {
            $order->customer_id = $request->customer_id;
            $order->customer_order = $request->customer_order;
            $order->auftrag = $request->auftrag;
            $order->destination_id = $request->destination_id;
            $order->customer_kw = $request->customer_kw;
            $order->production_kw = $request->production_kw;
            $order->delivery_kw = $request->delivery_kw;
            $order->eta = $request->eta;
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
            'message' => $validator->errors(),
            'type' => 'error'
        ], 406);
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
        $validator = Validator::make($request->all(), ['details_fields' => 'required'], ['details_fields.required' => 'Introduceti cel putin un camp!']);

        if ($validator->passes()) {
            if ($order->details_fields == null) {
                $order->details_fields = $request->details_fields;
                $order->save();

                $fields_arr = explode('|', $order->details_fields);
                $details = OrderDetail::where('order_id', $order->id)->get();
                foreach ($fields_arr as $field) {
                    foreach ($details as $detail) {
                        $data = json_decode($detail->details_json);
                        $data->$field = '';
                        $new_json_details = OrderDetail::find($detail->id);
                        $new_json_details->details_json = json_encode($data);
                        $new_json_details->save();
                    }
                }
            } else {
                $fields = explode('|', $order->details_fields);
                $extra = explode('|', trim($request->details_fields, '|'));

                $diff = array_diff($extra, $fields);

                $order->details_fields = rtrim($order->details_fields, '|') . '|' . implode('|', $diff);
                $order->save();

                $details = OrderDetail::where('order_id',$order->id)->get();
                foreach ($diff as $field) {
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

        return response()->json([
            'updated' => false,
            'message' => $validator->errors(),
            'type' => 'error'
        ], 406);
    }

    /**
     * Fetch the attached documents
     *
     * @param Order $order
     * @return Datatables
     */
    public function fetchAttachments(Order $order)
    {
        $documents = OrderAttachment::where('order_id', $order->id)->get();

        return DataTables::of($documents)
            ->addIndexColumn()
            ->addColumn('actions', function ($documents) {
                return view('orders.partials.actions_docs', ['documents' => $documents]);
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    /**
     * Upload the attachment
     *
     * @param Order $order
     * @return void
     */
    public function uploadAttachment(Order $order)
    {
        if(request()->hasFile('docs_file')) {
            Storage::putFileAs('/public/documents/' . $order->id, request()->file('docs_file'), request()->file('docs_file')->getClientOriginalName());
            $document = new OrderAttachment();
            $document->create([
                'order_id' => $order->id,
                'file' => request()->file('docs_file')->getClientOriginalName(),
                'user_id' => auth()->user()->id
            ]);
            return back();
        }
    }

    public function deleteAttachment(Order $order, OrderAttachment $document)
    {
        Storage::disk('documents')->delete($document->order_id . '/' . $document->file);
        $document->delete();

        return response()->json([
            'deleted' => true,
            'message' => 'Document sters',
            'type' => 'success',
        ]);
    }

    /**
     * Return the audits
     *
     * @param Request $request
     * @return collection
     */
    public function audits(Request $request)
    {
        return $this->getAudits(Order::class, $request->id);
    }

    /**
     * Copy the order a number of times specified by the user
     *
     * @param Order $order
     * @param Request $request
     * @return back
     */
    public function copy(Order $order, Request $request)
    {
        // get the order details
        $details = OrderDetail::where('order_id', $order->id)->get();

        // copy the order N times
        for ($i=0; $i < $request->copies; $i++) {
            // get the latest order number
            $latest_order = DB::table('orders')->latest()->first();

            $newOrder = new Order();
            $newOrder->order = $latest_order->order + 1;
            $newOrder->customer_id = $order->customer_id;
            $newOrder->destination_id = $order->destination_id;
            $newOrder->observations = $order->observations;
            $newOrder->details_fields = $order->details_fields;
            $newOrder->save();

            // copy the order details
            foreach ($details as $detail) {
                $newDetail = new OrderDetail();
                $newDetail->order_id = $newOrder->id;
                $newDetail->article_id = $detail->article_id;
                $newDetail->refinements_list = $detail->refinements_list;
                $newDetail->thickness = $detail->thickness;
                $newDetail->width = $detail->width;
                $newDetail->length = $detail->length;
                $newDetail->pcs = $detail->pcs;
                $newDetail->volume = $detail->volume;
                $newDetail->position = $detail->position;
                $newDetail->pcs_height = $detail->pcs_height;
                $newDetail->rows = $detail->rows;
                $newDetail->label = $detail->label;
                $newDetail->foil = $detail->foil;
                $newDetail->pal = $detail->pal;
                $newDetail->details_json = $detail->details_json;
                $newDetail->save();
            }
        }

        // redirect back with success message
        if ($request->copies > 1) {
            $message = 'Comanda a fost copiata cu succes de ' . $request->copies . ' ori!';
        } else {
            $message = 'Comanda a fost copiata cu succes!';
        }

        return back()->with('success', $message);
    }
}
