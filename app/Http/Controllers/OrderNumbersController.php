<?php

namespace App\Http\Controllers;

use App\OrderNumber;
use App\Traits\GetAudits;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class OrderNumbersController extends Controller
{
    use GetAudits;

    protected $rules = [
        'start_number' => 'required',
    ];

    protected $dictionary = [];

    /**
     * Show the all order numbers page
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('numbers.index');
    }

    /**
     * Get all the order numbers in the database and create Datatables
     *
     * @return mixed
     * @throws Exception
     */
    public function fetchAll()
    {
        $numbers = OrderNumber::all();

        return DataTables::of($numbers)
            ->addIndexColumn()
            ->editColumn('created_at', function($numbers) {
                return (new Carbon($numbers->created_at))->toDateTimeString();
            })
            ->addColumn('actions', function($numbers) {
                return view('numbers.partials.actions', ['numbers' => $numbers]);
            })
            ->make(true);
    }

    /**
     * Create a new order number from user input
     *
     * @param OrderNumber $number
     * @param Request $request
     * @return JsonResponse
     */
    public function store(OrderNumber $number, Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->passes()) {
            $number->start_number = $validator->valid()['start_number'];
            $number->save();

            return response()->json([
                'created' => true,
                'message' => 'Intrare adaugata in baza de date!',
                'type' => 'success'
            ], 201);
        }

        return response()->json([
            'created' => false,
            'message' => 'A aparut o eroare. Verificati daca ati completat numarul nou de comanda!',
            'type' => 'error'
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
        return $this->getAudits(OrderNumber::class, $request->id);
    }
}
