<?php

namespace App\Http\Controllers\API;

use App\Category;
use App\Tag;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InitController extends Controller
{
    public function index() {
        return response()->json([
            'category' => Category::where('active', true)->get(),
        ], 200);
    }   
}
