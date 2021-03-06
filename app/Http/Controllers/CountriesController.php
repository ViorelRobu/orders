<?php

namespace App\Http\Controllers;

use App\Country;
use App\Traits\GetAudits;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class CountriesController extends Controller
{
    use GetAudits;

    protected $rules = [
        'name' => 'required|unique:countries,name',
    ];

    protected $messages = [
        'name.required' => 'Introduceti un nume de tara!',
        'name.unique' => 'Mai exista o tara cu acest nume!'
    ];

    protected $dictionary = [];

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
     * @return JsonResponse
     */
    public function store(Country $country, Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules, $this->messages);

        if ($validator->passes()) {
            $country->name = $request->name;
            $country->save();

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
     * Update a country in the database
     *
     * @param Country $country
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Country $country, Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules, $this->messages);

        if ($validator->passes()) {
            $country->name = $request->name;
            $country->save();

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

    /**
     * Return the audits
     *
     * @param Request $request
     * @return collection
     */
    public function audits(Request $request)
    {
        return $this->getAudits(Country::class, $request->id);
    }
}
