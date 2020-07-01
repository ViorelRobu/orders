<?php

namespace App\Http\Controllers;

use App\Country;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class CountriesController extends Controller
{
    /**
     * Display the countries page to the user
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('countries.index');
    }

    /**
     * Get all the countries in the database and create Datatables
     *
     * @return mixed
     * @throws Exception
     */
    public function fetchAll()
    {
        $countries = Country::all();

        return DataTables::of($countries)
            ->addIndexColumn()
            ->addColumn('actions', function($countries) {
                return view('countries.partials.actions', ['country' => $countries]);
            })
            ->make(true);
    }

    /**
     * Create a new country from user input
     *
     * @param Country $country
     * @param Request $request
     * @return RedirectResponse
     */
    public function create(Country $country, Request $request)
    {
        $country->name = $request->name;
        $country->save();

        return back();
    }

    /**
     * Update a country in the database
     *
     * @param Country $country
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Country $country, Request $request)
    {
        $country->name = $request->name;
        $country->save();

        return back();
    }

    /**
     * Fetch the data from a single country
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function fetch(Request $request)
    {
        $country = Country::find($request->id);

        return (new JsonResponse(['message' => 'success', 'message_type' => 'success', 'data' => $country]));
    }
}
