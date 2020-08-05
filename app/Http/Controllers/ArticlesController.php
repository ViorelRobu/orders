<?php

namespace App\Http\Controllers;

use App\Article;
use App\ProductType;
use App\Quality;
use App\Refinement;
use App\Species;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ArticlesController extends Controller
{
    protected $rules = [
        'name' => 'required',
        'species_id' => 'required',
        'quality_id' => 'required',
        'product_type_id' => 'required',
        'default_refinements' => 'required',
        'thickness' => 'required',
        'width' => 'required'
    ];

    /**
     * Display the articles page to the user
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $species = Species::all();
        $quality = Quality::all();
        $products = ProductType::all();
        $refinements = Refinement::all();
        $selected = [];

        return view('articles.index', [
            'species' => $species,
            'quality' => $quality,
            'products' => $products,
            'refinements' => $refinements,
            'selected' => $selected,
            ]);
    }

    /**
     * Get all the articles in the database and create Datatables
     *
     * @return mixed
     * @throws Exception
     */
    public function fetchAll()
    {
        $articles = Article::all();
        $articles->map(function($item, $index) {
            $species = Species::find($item->species_id);
            $quality = Quality::find($item->quality_id);
            $product = ProductType::find($item->product_type_id);
            $item->species = $species->name;
            $item->quality = $quality->name;
            $item->product = $product->name;
        });

        return DataTables::of($articles)
            ->addIndexColumn()
            ->editColumn('default_refinements', function($articles) {
                $refinements = [];
                $default_refinements = explode(',', $articles->default_refinements);
                foreach($default_refinements as $ref) {
                    $refinement = Refinement::find($ref);
                    $refinements[] = $refinement->name;
                }
                return $refinements;
            })
            ->addColumn('actions', function ($articles) {
                return view('articles.partials.actions', ['article' => $articles]);
            })
            ->make(true);
    }

    /**
     * Create a new article from user input
     *
     * @param Article $article
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Article $article, Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->passes()) {
            $article->name = $validator->valid()['name'];
            $article->species_id = $validator->valid()['species_id'];
            $article->quality_id = $validator->valid()['quality_id'];
            $article->product_type_id = $validator->valid()['product_type_id'];
            $article->default_refinements = implode(',', $validator->valid()['default_refinements']);
            $article->thickness = $validator->valid()['thickness'];
            $article->width = $validator->valid()['width'];
            $article->save();

            return response()->json([
                'created' => true,
                'message' => 'Intrare adaugata in baza de date!',
                'type' => 'success'
            ], 201);
        }

        return response()->json([
            'created' => false,
            'message' => 'A aparut o eroare. Completati toate campurile!',
            'type' => 'error'
        ]);
    }

    /**
     * Update a article in the database
     *
     * @param Article $article
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Article $article, Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->passes()) {
            $article->name = $validator->valid()['name'];
            $article->species_id = $validator->valid()['species_id'];
            $article->quality_id = $validator->valid()['quality_id'];
            $article->product_type_id = $validator->valid()['product_type_id'];
            $article->default_refinements = implode(',', $validator->valid()['default_refinements']);
            $article->thickness = $validator->valid()['thickness'];
            $article->width = $validator->valid()['width'];
            $article->save();

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
     * Fetch the data from a single article
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function fetch(Request $request)
    {
        $article = Article::find($request->id);
        $default_refinements = explode(',', $article->default_refinements);
        $article->refinements_arr = $default_refinements;

        return (new JsonResponse(['message' => 'success', 'message_type' => 'success', 'data' => $article]));
    }
}