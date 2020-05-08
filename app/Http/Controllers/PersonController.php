<?php

namespace App\Http\Controllers;

use App\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PersonController extends Controller
{
    private $basicProperties = ['id', 'last_name', 'first_name', 'mail'];
    private $properties = ['*'];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $orderByArr = $request->input('order-by', 'first_name');
        $orderByArr = $this->stringToArray($orderByArr);

        $personQuery = Person::select(...$this->basicProperties);

        // check for serach
        $this->checkSearch($personQuery, $search);

        $personQuery = $this->executeQuery($personQuery, null, null, $orderByArr);
//        if (is_null($personQuery)) { $personQuery = []; }
        return response()->json($personQuery, 200);
    }

    /**
     * Check for search
     *
     * @param $query
     * @param $search
     * @return string
     */
    private function checkSearch($query, $search) {
        if(!isset($query)) { return $query; }

        if(!is_null($search)) {
            $searchTerms = $this->stringToArray($search, ' ');

            // make sure search is in brackets (joins must be outside)
            $query = $query->where(function($query) use ($searchTerms) {
                // PROJECT SEARCH
                for($i = 0, $max = count($searchTerms); $i < $max; $i++) {
                    $term = str_replace('_', '\_', mb_strtolower('%' . $searchTerms[$i] . '%'));
                    $query->whereRaw("(Lower(first_name) LIKE ? OR Lower(last_name) LIKE ?)", [$term, $term]);
                }
            });
        }
        return $query;
    }
}
