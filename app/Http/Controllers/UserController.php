<?php

namespace App\Http\Controllers;

use App\Contract;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request) {

        $search = $request->input('search');

        $userQuery = User::select('*'); // select all from contract

        $users = $this->executeQuery($userQuery);

        // if contracts date is null return empty array
        if (is_null($users)) { $users = []; }// set contract to an empty array

        // return a json response of contract data
        return response()->json($users, 200);
    }

    /**
     * GET /api/users/{id}
     * Get a specific user
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id) {

        if(!is_numeric($id)) {
            throw new \InvalidArgumentException('Bad argument: Important credential \'id\' is in bad format.', 400);
        }

        $user = User::findOrFail($id);

        return response()->json($user, 200);
    }


    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function user()
    {
        $user = auth()->user();
        return response()->json($user, 200);
    }

}
