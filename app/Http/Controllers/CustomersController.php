<?php

namespace App\Http\Controllers;

use App\Customer;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class CustomersController extends Controller
{
    /**
     * Show the all customers page
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('customers.index');
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

        return DataTables::of($customers)
            ->addIndexColumn()
            ->addColumn('actions', function($customers) {
                return view('customers.partials.actions', ['customer' => $customers]);
            })
            ->make(true);
    }

    /**
     * Create a new customer inside the database
     *
     * @param Customer $customer
     * @param Request $request
     * @return RedirectResponse
     */
    public function create(Customer $customer, Request $request)
    {
        $customer->name = $request->name;
        $customer->country_id = $request->country_id;
        $customer->save();

        return back();
    }

    /**
     * Update the customer's details in the database
     *
     * @param Customer $customer
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Customer $customer, Request $request)
    {
        $customer->name = $request->name;
        $customer->country_id = $request->country_id;
        $customer->save();

        return back();
    }
}
