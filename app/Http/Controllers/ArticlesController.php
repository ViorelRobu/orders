<?php

namespace App\Http\Controllers;

use App\Article;
use App\Imports\ArticlesImport;
use App\ProductType;
use App\Quality;
use App\Refinement;
use App\Species;
use App\Traits\GetAudits;
use App\Traits\RefinementsTranslator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ArticlesController extends Controller
{
    use RefinementsTranslator;
    use GetAudits;

    protected $rules = [
        'name' => 'required',
        'species_id' => 'required',
        'quality_id' => 'required',
        'product_type_id' => 'required',
        'thickness' => 'required',
        'width' => 'required'
    ];

    protected $dictionary = [
        'species_id' => [
            'new_name' => 'specie',
            'model' => 'App\Species',
            'property' => 'name'
        ],
        'quality_id' => [
            'new_name' => 'calitate',
            'model' => 'App\Quality',
            'property' => 'name'
        ],
        'product_type_id' => [
            'new_name' => 'tip produs',
            'model' => 'App\ProductType',
            'property' => 'name'
        ],
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
            $article->name = $request->name;
            $article->species_id = $request->species_id;
            $article->quality_id = $request->quality_id;
            $article->product_type_id = $request->product_type_id;
            $article->thickness = $request->thickness;
            $article->width = $request->width;
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

    public function import()
    {
        try {
            if (request()->hasFile('articles')) {
                $file = request()->file('articles');
                Excel::import(new ArticlesImport, $file);
            }
            return back()->with('success', 'Articolele au fost importate cu success!');
        } catch (\Throwable $th) {
            return back()->with('failure', 'Articolele nu au putut fi importate!');
        }
    }

    /**
     * Return the audits
     *
     * @param Request $request
     * @return collection
     */
    public function audits(Request $request)
    {
        return $this->getAudits(Article::class, $request->id);
    }
}
