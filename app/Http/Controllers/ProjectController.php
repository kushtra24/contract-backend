<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    // variables
    private $basicProperties = [ 'projects.id', 'title', 'project_lead', 'deputy_project_lead', 'sales'];
    private $properties = [ '*' ];

    /**
     * GET /api/projects
     * Get all or multiple projects
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request) {

        $search = $request->get('search');

        $orderByArr = $request->get('order-by', 'title');

        // parse string
        $orderByArr = $this->stringToArray($orderByArr);

        // check for 'mode'
        $projectQuery = Project::select(...$this->basicProperties);

        // check for search
        $this->checkSearch($projectQuery, $search);

        // execute query
        $projects = $this->executeQuery($projectQuery, null, null, $orderByArr);

        if(is_null($projects)) { $projects = []; }
        return response()->json($projects, 200);
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
                    $query->whereRaw("(Lower(title) LIKE ?)", [$term, $term]);
                }
            });
        }
        return $query;
    }

}
