<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Person;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    private $basicProperties = [ 'id', 'sap_id', 'center_leader_id', 'segment_leader_id', 'sales_id', 'name', 'abbreviation', 'active', 'number'];
    private $properties = [ '*' ];


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSearchedCustomers(Request $request) {
        $search = $request->input('search');

        $orderByArr = $request->input('order-by', 'name');

        $orderByArr = $this->stringToArray($orderByArr);

        $customerQuery = Customer::select(...$this->basicProperties);

        // check for search
        $this->checkSearch($customerQuery, $search);

        // execute query
        $customers = $this->executeQuery($customerQuery, null, null, $orderByArr);

        if(is_null($customers)) { $customers = []; }
        return response()->json($customers, 200);
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
                    $query->whereRaw("(Lower(name) LIKE ? OR Lower(abbreviation) LIKE ?)", [$term, $term]);
                }
            });
        }
        return $query;
    }
}
