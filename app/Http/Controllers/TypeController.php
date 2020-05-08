<?php

namespace App\Http\Controllers;

use App\Type;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    /**
     * Display list of contract types
     *
     */
    public function getContractTypes(Request $request) {

        $typeQuery = Type::select('id', 'name');

        $types = $this->executeQuery($typeQuery);

        return response()->json($types, 200);
    }


}
