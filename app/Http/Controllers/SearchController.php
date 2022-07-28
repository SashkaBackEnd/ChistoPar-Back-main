<?php

namespace App\Http\Controllers;

use App\Models\Bath;
use App\Models\Specialist;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request) {
        $first = ucfirst($request->s);
        $bath = Bath::where('name', 'ilike', "%$request->s%")->where('name', 'ilike', "%$first%")->paginate(15);
        $specialist= Specialist::where('name', 'ilike', "%$request->s%")->where('name', 'ilike', "%$first%")->paginate(15);
        return response([
            'bath' => $bath,
            'specialist' => $specialist
        ]);
    }
}
