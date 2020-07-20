<?php

namespace App\Http\Controllers;

use App\Quality;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class QualityController extends Controller
{
    protected $rules = [
        'name' => 'required|unique:quality,name',
    ];

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
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->passes()) {
            $quality->name = $validator->valid()['name'];
            $quality->save();

            return response()->json([
                'created' => true,
                'message' => 'Intrare adaugata in baza de date!',
                'type' => 'success'
            ], 201);
        }

        return response()->json([
            'created' => false,
            'message' => 'A aparut o eroare. Verificati daca calitatea introdusa nu exista deja in baza de date!',
            'type' => 'error'
        ]);
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
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->passes()) {
            $quality->name = $validator->valid()['name'];
            $quality->save();

            return response()->json([
                'updated' => true,
                'message' => 'Intrare modificata in baza de date!',
                'type' => 'success'
            ]);
        }

        return response()->json([
            'updated' => false,
            'message' => 'A aparut o eroare. Reincercati!',
            'type' => 'error'
        ]);
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
}
