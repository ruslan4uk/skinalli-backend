<?php

namespace App\Http\Controllers\API\Catalog;

use App\Category;
use App\Tag;
use App\Photo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index(Request $request) 
    {

        $photo = Photo::with(['photoCategory'])
            ->whereHas('photoCategory', function($q) use($request) {
                !$request->get('subcatalog') ?: $q->where('slug', '=', $request->get('subcatalog'));
            })
            ->whereYear('created_at', '=', $request->get('year') ?: null)
            ->whereMonth('created_at', '=', $request->get('month') ?: null)
            ->where('color', 'LIKE', '%' . $request->get('color') . '%')
            ->orderBy('created_at', $request->get('sort') === 'asc' ? 'asc' : 'desc')
            ->paginate(8);

        return \response()->json([
            'data' => $photo,
        ], 200);

    }

    public function show($slug, Request $request) 
    {
        $data = Photo::where(['active' => true, 'slug' => $slug])
            ->with('photoTag')
            ->firstOrFail();

        return \response()->json([
            'data' => $data
        ], 200);
    }
}
