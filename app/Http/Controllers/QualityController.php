<?php

namespace App\Http\Controllers;

use App\Quality;
use App\Traits\GetAudits;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class QualityController extends Controller
{
    use GetAudits;

    protected $rules = [
        'name' => 'required|unique:quality,name',
    ];

    protected $messages = [
        'name.required' => 'Introduceti calitatea!',
        'name.unique' => 'Aceasta calitate mai exista!',
    ];

    protected $dictionary = [];

    /**
     * Show the all quality page
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('quality.index');
    }

    /**
     * Get all the quality in the database and create Datatables
     *
     * @return mixed
     * @throws Exception
     */
    public function fetchAll()
    {
        $quality = Quality::all();

        return DataTables::of($quality)
            ->addIndexColumn()
            ->addColumn('actions', function ($quality) {
                return view('quality.partials.actions', ['quality' => $quality]);
            })
            ->make(true);
    }

    /**
     * Create a new quality from user input
     *
     * @param Quality $quality
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Quality $quality, Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules, $this->messages);

        if ($validator->passes()) {
            $quality->name = $request->name;
            $quality->save();

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
     * Update a quality in the database
     *
     * @param Quality $species
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Quality $quality, Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules, $this->messages);

        if ($validator->passes()) {
            $quality->name = $request->name;
            $quality->save();

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
     * Fetch the data from a single quality
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function fetch(Request $request)
    {
        $quality = Quality::find($request->id);
        return (new JsonResponse(['message' => 'success', 'message_type' => 'success', 'data' => $quality]));
    }

    /**
     * Return the audits
     *
     * @param Request $request
     * @return collection
     */
    public function audits(Request $request)
    {
        return $this->getAudits(Quality::class, $request->id);
    }
}
