<?php

namespace App\Http\Controllers;

use App\contract;
use App\Helpers\Utils;
use Illuminate\Http\Request;

class ContractController extends Controller
{

    private $basicProperties = [
        'typeId',
        'title',
        'temporary',
        'endDate',
        'originalAtTeamAssistant',
        'rating',
        'ratingBg',
        'submittingPersonId',
        'customerNumber',
        'signedDate',
        'customerId',
        'linkedContractsId',
        'additionalPersons',
        'contractId',
        'projectId',
        'personId',
        'linkedContractsId',
        'deletedPersonId',
        'deletedProjectId',
        'deletedLinkedContractsId',
    ];

    private $migrateProperties = [
        'typeId' => 'type_id',
        'endDate' => 'end_date',
        'originalAtTeamAssistant' => 'original_at_team_assistant',
        'ratingBg' => 'rating_bg',
        'submittingPersonId' => 'submitting_person_id',
        'customerNumber' => 'customer_number',
        'signedDate' => 'signed_date',
        'customerId' => 'customer_id',
        'contractId' => 'contract_id',
        'projectId' => 'project_id',
        'personId' => 'person_id',
        'linkedContractsId' => 'linked_contracts_id',
        'deletedPersonId' => 'deleted_person_id',
        'deletedProjectId' => 'deleted_project_id',
        'deletedLinkedContractsId' => 'deleted_linked_contracts_id',
    ];

    //validate
    private $rulesCreateContract = null;

    /**
     * UserController constructor.
     */
    public function __construct(/*RequestHttp $requestService*/)
    {
        $this->reset();
    }


    /**
     * Reset variables
     */
    private function reset() {

        // rules
        $this->rulesCreateContract = [
            'title' => 'required',
            'signed_date' => 'required',
            'type_id' => 'required',
        ];

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // get the logged in user
//        $user = Auth::user();

//     check if user is logged in and has the permission to write contracts
//        if (!isset($user) || !$user->ability([], ["write-contract"])) {
//            throw new \InvalidArgumentException('You do not have permission to post', 403);
//        }

        /**
         * incoming properties from the fronted request
         */
        $data = $this->getOnly($request->all(), $this->basicProperties);
        /**
         * convert from camelcase to snake case
         */
        $data = $this->migrateProperty($data, $this->migrateProperties);

        // validate the data
        $this->validateData($data, $this->rulesCreateContract);

        // clean the data array
        $data = $this->cleanArray($data);

        // get he contract
        $contract = new Contract($data);
        // save
        $contract->save();

        // return a json response
        return response()->json(['id' => $contract->id], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\contract  $contract
     * @return \Illuminate\Http\Response
     */
    public function show(contract $contract)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\contract  $contract
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, contract $contract)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\contract  $contract
     * @return \Illuminate\Http\Response
     */
    public function destroy(contract $contract)
    {
        //
    }

    /**
     * Get only given keys from given data
     * @param $data
     * @param $keys
     * @return array
     */
    public static function getOnly($data, $keys) {
        if(!isset($keys) || !is_countable($keys) || !count($keys)) { return []; }

        $result = [];
        foreach ($keys as $key) {
            if(!isset($key)) { continue; }

            $value = null;
            if(array_key_exists($key, $data)) {
                $value = $data[$key];

            } else if(isset($data[$key])) {
                $value = $data[$key];
            }

            $result[$key] = $value;
        }

        return $result;
    }
}
