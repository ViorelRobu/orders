<?php

namespace App\Http\Controllers;

use App\ProductType;
use App\Traits\GetAudits;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class ProductTypesController extends Controller
{
    use GetAudits;

    protected $rules = [
        'name' => 'required|unique:product_types,name',
    ];

    protected $messages = [
        'name.required' => 'Introduceti numele tipului de produs!',
        'name.unique' => 'Mai exista un tip de produs cu acelasi nume!',
    ];

    protected $dictionary = [];

    /**
     * Show the all products page
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('products.index');
    }

    /**
     * Get all the products in the database and create Datatables
     *
     * @return mixed
     * @throws Exception
     */
    public function fetchAll()
    {
        $products = ProductType::all();

        return DataTables::of($products)
            ->addIndexColumn()
            ->addColumn('actions', function ($products) {
                return view('products.partials.actions', ['products' => $products]);
            })
            ->make(true);
    }

    /**
     * Create a new product from user input
     *
     * @param ProductType $product
     * @param Request $request
     * @return JsonResponse
     */
    public function store(ProductType $product, Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules, $this->messages);

        if ($validator->passes()) {
            $product->name = $validator->valid()['name'];
            $product->save();

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
     * Update a product in the database
     *
     * @param ProductType $product
     * @param Request $request
     * @return JsonResponse
     */
    public function update(ProductType $product, Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules, $this->messages);

        if ($validator->passes()) {
            $product->name = $validator->valid()['name'];
            $product->save();

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
     * Fetch the data from a single product
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function fetch(Request $request)
    {
        $product = ProductType::find($request->id);
        return (new JsonResponse(['message' => 'success', 'message_type' => 'success', 'data' => $product]));
    }

    /**
     * Return the audits
     *
     * @param Request $request
     * @return collection
     */
    public function audits(Request $request)
    {
        return $this->getAudits(ProductType::class, $request->id);
    }
}
