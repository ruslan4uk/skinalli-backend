<?php

namespace App\Http\Controllers\API;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TestController extends Controller
{
    public function index() {

        return response()->json([
            'data' => User::all(),
        ], 200);

    }
}
