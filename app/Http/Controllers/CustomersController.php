<?php

namespace App\Http\Controllers;

use App\Country;
use App\Customer;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class CustomersController extends Controller
{
    protected $rules = [
            'fibu' => 'required',
            'name' => 'required',
            'country_id' => 'required'
        ];

    /**
     * Show the all customers page
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $countries = Country::all();
        return view('customers.index', ['countries' => $countries]);
    }

    /**
     * Fetch all the customers from the database
     *
     * @return mixed
     * @throws Exception
     */
    public function fetchAll()
    {
        $customers = Customer::all();

        $customers->map(function($item, $index){
            $country = Country::find($item->country_id);
            $item->country_id = $country->name;
        });

        return DataTables::of($customers)
            ->addIndexColumn()
            ->addColumn('actions', function($customers) {
                return view('customers.partials.actions', ['customer' => $customers]);
            })
            ->make(true);
    }

    /**
     * Get the details for a single customer
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function fetch(Request $request)
    {
        $customer = Customer::find($request->id);

        $country = Country::find($customer->country_id);
        $customer->country = $country->name;


        return (new JsonResponse(['message' => 'success', 'message_type' => 'success', 'data' => $customer]));
    }

    /**
     * Create a new customer inside the database
     *
     * @param Customer $customer
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Customer $customer, Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->passes()) {
            $customer->fibu = $validator->valid()['fibu'];
            $customer->name = $validator->valid()['name'];
            $customer->country_id = $validator->valid()['country_id'];
            $customer->save();

            return response()->json([
                'created' => true,
                'message' => 'Intrare adaugata in baza de date!',
                'type' => 'success'
            ], 201);
        }

        return response()->json([
            'created' => false,
            'message' => 'A aparut o eroare. Verificati daca ati completat corect toate campurile marcate cu stea si reincercati.',
            'type' => 'error'
        ]);
    }

    /**
     * Update the customer's details in the database
     *
     * @param Customer $customer
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Customer $customer, Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->passes()) {
            $customer->fibu = $validator->valid()['fibu'];
            $customer->name = $validator->valid()['name'];
            $customer->country_id = $validator->valid()['country_id'];
            $customer->save();
            return response()->json([
                'updated' => true,
                'message' => 'Intrare modificata in baza de date!',
                'type' => 'success'
            ]);
        }

        return response()->json([
            'updated' => false,
            'message' => 'A aparut o eroare. Verificati daca ati completat corect toate campurile marcate cu stea si reincercati.',
            'type' => 'error'
        ]);
    }
}
