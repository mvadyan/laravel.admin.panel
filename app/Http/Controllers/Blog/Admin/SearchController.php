<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Models\Admin\Currency;
use App\Models\Admin\Product;
use Illuminate\Http\Request;
use MetaTag;

/**
 * Class SearchController
 * @package App\Http\Controllers\Blog\Admin
 */
class SearchController extends AdminBaseController
{
    /**
     * SearchController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = !empty(trim($request->search)) ? trim($request->search) : null;
        $products = Product::where('title', 'LIKE', '%' . $query . '%')
            ->get()
            ->all();
        $currency = Currency::where('base', '1')->first();


        MetaTag::setTags(['title' => 'Результаты поиска']);
        return view('blog.admin.search.result', compact('query', 'products', 'currency'));

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $search = $request->get('term');
        $result = Product::select('title')
            ->where('title', 'LIKE', '%' . $search . '%')
            ->pluck('title');

        return response()->json($result);
    }
}
