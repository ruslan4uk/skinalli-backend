<?php

namespace App\Http\Controllers\API\Catalog;

use App\Category;
use App\Tag;
use App\Photo;
use App\Helpers\Filters\PhotoFilter;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // $query = Photo::with(['photoCategory'])
        //         ->whereHas('photoCategory', function($q) use($request) {
        //             !$request->get('subcatalog') ?: $q->where('slug', '=', $request->get('subcatalog'));
        //         });

        $photo = (new PhotoFilter(Photo::with('photoCategory'), $request))->apply()->paginate(8);

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
