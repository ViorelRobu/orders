<?php

namespace App\Http\Controllers;

use App\Species;
use App\Traits\GetAudits;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class SpeciesController extends Controller
{
    use GetAudits;

    protected $rules = [
        'name' => 'required|unique:species,name',
    ];

    protected $messages = [
        'name.required' => 'Introduceti denumirea speciei!',
        'name.unique' => 'Exista inca o specie cu acelasi nume!',
    ];

    protected $dictionary = [];

    /**
     * Show the all species page
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('species.index');
    }

    /**
     * Get all the species in the database and create Datatables
     *
     * @return mixed
     * @throws Exception
     */
    public function fetchAll()
    {
        $species = Species::all();

        return DataTables::of($species)
            ->addIndexColumn()
            ->addColumn('actions', function ($species) {
                return view('species.partials.actions', ['species' => $species]);
            })
            ->make(true);
    }

    /**
     * Create a new species from user input
     *
     * @param Species $species
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Species $species, Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules, $this->messages);

        if ($validator->passes()) {
            $species->name = $request->name;
            $species->save();

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
     * Update a species in the database
     *
     * @param Species $species
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Species $species, Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules, $this->messages);

        if ($validator->passes()) {
            $species->name = $request->name;
            $species->save();

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
     * Fetch the data from a single species
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function fetch(Request $request)
    {
        $species = Species::find($request->id);
        return (new JsonResponse(['message' => 'success', 'message_type' => 'success', 'data' => $species]));
    }

    /**
     * Return the audits
     *
     * @param Request $request
     * @return collection
     */
    public function audits(Request $request)
    {
        return $this->getAudits(Species::class, $request->id);
    }
}
