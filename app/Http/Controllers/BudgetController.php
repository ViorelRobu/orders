<?php

namespace App\Http\Controllers;

use App\Article;
use App\Budget;
use App\ProductType;
use App\Traits\GetAudits;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class BudgetController extends Controller
{
    use GetAudits;

    protected $dictionary = [
        'product_type_id' => [
            'new_name' => 'Product type',
            'model' => 'App\ProductType',
            'property' => 'name'
        ]
    ];

    protected $rules = [
        'group' => 'required',
        'year' => 'required',
        'week' => 'required',
        'volume' => 'required',
    ];

    protected $messages = [
        'group.required' => 'Selectati tipul produsului!',
        'year.required' => 'Selectati un an!',
        'week.required' => 'Selectati o saptamana!',
        'volume.required' => 'Introduceti volumul!',
    ];

    /**
     * Display the budget index page
     *
     * @return view
     */
    public function index()
    {
        $product_group = ProductType::all();
        $year = Carbon::now()->year;
        $weeks = [];
        for ($i=1; $i < 54; $i++) {
            $weeks[] = $i;
        }

        return view('budget.index',
            [
                'group' => $product_group,
                'year' => $year,
                'weeks' => $weeks
            ]);
    }

    /**
     * Fetch all the budget entries in the DB
     *
     * @return mixed
     * @throws Exception
     */
    public function fetchAll()
    {
        $budget = Budget::all();

        return DataTables::of($budget)
            ->addColumn('actions', function ($budget) {
                return view('budget.partials.actions', ['budget' => $budget]);
            })
            ->editColumn('product_type_id', function($budget) {
                $product_type = ProductType::find($budget->product_type_id);
                return $product_type->name;
            })
            ->make(true);
    }

    /**
     * Fetch the data from a budget entry
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function fetch(Request $request)
    {
        $budget = Budget::find($request->id);

        return (new JsonResponse(['message' => 'success', 'message_type' => 'success', 'data' => $budget]));
    }

    /**
     * Create a new budget entry from user input
     *
     * @param Budget $budget
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Budget $budget, Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules, $this->messages);

        if ($validator->passes()) {
            $budget->product_type_id = $request->group;
            $budget->year = $request->year;
            $budget->week = $request->week;
            $budget->volume = $request->volume;
            $budget->save();

            return response()->json([
                'created' => true,
                'message' => 'Intrare adaugata in baza de date!',
                'type' => 'success'
            ], 201);
        }

        return response()->json([
            'created' => false,
            'message' => $validator->errors(),
            'type' => 'error'
        ], 406);
    }

    /**
     * Update a budget entry in the database
     *
     * @param Budget $budget
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules, $this->messages);

        if ($validator->passes()) {
            $budget = Budget::find($request->id);
            $budget->product_type_id = $request->group;
            $budget->year = $request->year;
            $budget->week = $request->week;
            $budget->volume = $request->volume;
            $budget->save();

            return response()->json([
                'updated' => true,
                'message' => 'Intrare modificata in baza de date!',
                'type' => 'success'
            ]);
        }

        return response()->json([
            'updated' => false,
            'message' => $validator->errors(),
            'type' => 'error'
        ], 406);
    }

    /**
     * Check if a new combination of product group, year and week is unique
     *
     * @param Budget $budget
     * @param Request $request
     * @return JsonResponse
     */
    public function isUnique(Request $request)
    {
        if ($request->new) {
            $budget = Budget::where('product_type_id', $request->group)
                ->where('year', $request->year)
                ->where('week', $request->week)
                ->get();
            return response()->json([
                'exists' => $budget->count() == 0 ? false:true,
            ]);
        } else {
            $budget = Budget::where('id', '!=', $request->id)
                ->where('product_type_id', $request->group)
                ->where('year', $request->year)
                ->where('week', $request->week)
                ->get();
            return response()->json([
                'exists' => $budget->count() == 0 ? false : true,
            ]);
        }
    }

    /**
     * Return the audits
     *
     * @param Request $request
     * @return collection
     */
    public function audits(Request $request)
    {
        return $this->getAudits(Budget::class, $request->id);
    }
}
