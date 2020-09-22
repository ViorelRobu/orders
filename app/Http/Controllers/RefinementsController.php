<?php

namespace App\Http\Controllers;

use App\Refinement;
use App\Traits\GetAudits;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class RefinementsController extends Controller
{
    use GetAudits;

    protected $rules = [
        'name' => 'required',
        'description' => 'required',
    ];

    protected $messages = [
        'name.required' => 'Introduceti denumirea finisajului!',
        'description.required' => 'Introduceti descrierea finisajului!',
    ];

    protected $dictionary = [];

    /**
     * Show the all refinements page
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('refinements.index');
    }

    /**
     * Get all the refinements in the database and create Datatables
     *
     * @return mixed
     * @throws Exception
     */
    public function fetchAll()
    {
        $refinements = Refinement::all();

        return DataTables::of($refinements)
            ->addIndexColumn()
            ->addColumn('actions', function ($refinements) {
                return view('refinements.partials.actions', ['refinements' => $refinements]);
            })
            ->make(true);
    }

    /**
     * Create a new refinements from user input
     *
     * @param Refinement $refinements
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Refinement $refinement, Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules, $this->messages);

        if ($validator->passes()) {
            $refinement->name = $request->name;
            $refinement->description = $request->description;
            $refinement->save();

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
     * Update a refinements in the database
     *
     * @param Refinement $refinement
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Refinement $refinement, Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules, $this->messages);

        if ($validator->passes()) {
            $refinement->name = $request->name;
            $refinement->description = $request->description;
            $refinement->save();

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
     * Fetch the data from a single refinement
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function fetch(Request $request)
    {
        $refinement = Refinement::find($request->id);
        return (new JsonResponse(['message' => 'success', 'message_type' => 'success', 'data' => $refinement]));
    }

    /**
     * Return the audits
     *
     * @param Request $request
     * @return collection
     */
    public function audits(Request $request)
    {
        return $this->getAudits(Refinement::class, $request->id);
    }
}
