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
        $query = Photo::query();
        $query->with(['photoCategory'])
                ->whereHas('photoCategory', function($q) use($request) {
                    !$request->get('subcatalog') ?: $q->where('slug', '=', $request->get('subcatalog'));
                });
        /**
         * Sort in month
         */
        $query->when($request->get('month'), function ($q) use($request) {
            return $q->whereMonth('created_at', '=', $request->get('month'));
        });
        /**
         * Sort in year
         */
        $query->when($request->get('year'), function ($q) use($request) {
            return $q->whereYear('created_at', '=', $request->get('year'));
        });

        $query->where('color', 'LIKE', '%' . $request->get('color') . '%');
        $query->orderBy('created_at', $request->get('sort') === 'asc' ? 'asc' : 'desc');
        $photo = $query->paginate(8);

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
