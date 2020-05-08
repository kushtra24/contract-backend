<?php

namespace App\Http\Controllers;

use App\Contract;
use App\Customer;
use App\Helpers\Utils;
use App\Person;
use App\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Input\Input;

class ContractController extends Controller
{

    private $basicProperties = [
        'typeId',
        'title',
        'temporary',
        'endDate',
        'isOriginal',
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
        'isOriginal' => 'is_original',
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
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {

//        $user = Auth::user();

        $search = $request->input('search');
//        $contractType = $request->input('type');
//        $customerId = $request->input('customerId');
//        $personId = $request->input('personId');
        $orderByArr = $request->input('order-by', 'title'); // default order
//        $orderType = $request->input('order-type', 'asc'); // order type
        $this->orderByArr = $this->stringToArray($orderByArr); // to array

        $contractQuery = Contract::select('*'); // select all from contract

        $this->checkSearch($contractQuery, $search); // check for search
//        $this->checkContractType($contractQuery, $contractType); // check for search
//        $this->checkCustomerFilter($contractQuery, $customerId); // check for search
//        $this->checkPersonFilter($contractQuery, $personId); // check for search
//        $contracts = $this->executeQuery($contractQuery, $this->orderByArr, $orderType); // execute the query
        $contracts = $this->executeQuery($contractQuery, null, null, $orderByArr);
//        $this->getDataForIds($contracts); // get the data for ids like person, projects and linked contract

        // if contracts date is null return empty array
        if (is_null($contracts)) { $contracts = []; }// set contract to an empty array

        // return a json response of contract data
        return response()->json($contracts, 200);
    }

    /**
     * find the searched contract
     * @param $query
     * @param $search
     * @return mixed
     */
    private function checkSearch(&$query, $search) {
        if (!isset($query)) {
            return $query;
        }

        if (!is_null($search)) {
            $localDb = env('DB_DATABASE');
            $bigPictureTable = env('DB_DATABASE_BP_PUBLIC');
            $searchTerms = $this->stringToArray($search, ' ');
            $query->leftJoin($bigPictureTable . '.customer', $localDb . '.contracts.customer_id', '=', $bigPictureTable .'.customer.id');
            $query = $query->where(function ($query) use ($searchTerms) {
                for ($i = 0, $max = count($searchTerms); $i < $max; $i++) {
                    $term = str_replace('_', '\_', mb_strtolower('%' . $searchTerms[$i] . '%'));
                    $query->whereRaw("(Lower(contracts.title) LIKE ?)", [$term, $term])
                        ->orWhereRaw("(Lower(customer.name) LIKE ?)", [$term, $term]);
                }
            });
            $this->orderByArr = 'end_date';
        }
    }

    /**
     * check contract type
     * @param $query
     * @param $type
     * @return mixed
     */
    private function checkContractType(&$query, $type) {
        if (!isset($query)) { return $query; }

        if (!is_null($type)) {
            $query = $query->where( 'type_id', $type);
        }
    }

    /**
     * @param $contractQuery
     * @param $customerId
     * @return mixed
     */
    private function checkCustomerFilter(&$contractQuery, $customerId) {
        if (!isset($contractQuery)) { return $contractQuery; }

        if (!is_null($customerId)) {
            $contractQuery = $contractQuery->where('customer_id', $customerId);
        }
    }

    /**
     * @param $contractQuery
     * @param $personId
     * @return mixed
     */
    private function checkPersonFilter(&$contractQuery, $personId) {
        if (!isset($contractQuery)) { return $contractQuery; }

        if (!is_null($personId)) {
            $contractQuery = $contractQuery->where('submitting_person_id', $personId);
        }
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
//        $this->validateData($data, $this->rulesCreateContract);
//
        // clean the data array
        $data = $this->cleanArray($data);
        // get he contract
        $contract = new Contract($data);
        // save
        $contract->save();

        $this->storeOtherDetails($data, $contract);

        // return a json response
        return response()->json(['id' => $contract->id], 201);
    }

    /**
     * attach the request data to the corresponding db table
     * @param $data
     * @param $contract
     */
    public function storeOtherDetails($data, $contract) {

        // attach project Ids to contract_project
        if (isset($data['project_id'])) {
            foreach ($data['project_id'] as $projectId) {
                $contract->projects()->attach($projectId);
            }
        }

        // attach additional people ids to contract_person
        if (isset($data['person_id'])) {
            foreach ($data['person_id'] as $people) {
                $contract->people()->attach($people);
            }
        }

        if (isset($data['linked_contracts_id'])) {
            foreach ($data['linked_contracts_id'] as $linked) {
                $contract->contracts()->attach($linked);
            }
        }


    }

    /**
     * get contract information from other models depending on the id
     * @param $contracts
     */
    public function getDataForIds($contracts) {

        // get the get data from Person depending on the id on the contract
        if (isset($contracts) && is_countable($contracts)) {
            foreach ($contracts as &$contract) {

                if (!isset($contract)) { continue; }

                // get data
                $segmentLeader = Person::find($contract['segment_id']);
                $customerName = Customer::find($contract['customer_id']);
                $whoSubmitted = Person::find($contract['submitting_person_id']);
                $contractType = Type::find($contract['type_id']);


                // check if has data and get the segment leader name and firstname
                if (isset($segmentLeader)) {
                    $contract['segmentLeader'] = ['lastName' => $segmentLeader->lastName, 'firstName' => $segmentLeader->firstName];
                }

                // check if has data and get the customer name
                if (isset($customerName)) {
                    $contract['customerName'] = $customerName->name;
                }

                // check if has data, and get the name of who submitted the contract
                if (isset($whoSubmitted)) {
                    $contract['whoSubmitted'] = ['lastName' => $whoSubmitted->lastName, 'firstName' => $whoSubmitted->firstName];
                }

                // check is has data, and get contract type name
                if (isset($contractType)) {
                    $contract['contractType'] = $contractType->name;
                }

                // check if end date is set and convert to a different format
                if (is($contract->end_date)) {
                    $contract['endDateConverted'] = date('d/m/Y', strtotime($contract->end_date));
                }

                // check if end date is set and convert to a different format
                if (is($contract->signed_date)) {
                    $contract['signedDateConverted'] = date('d/m/Y', strtotime($contract->signed_date));
                }

            }
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Contract  $contract
     * @return \Illuminate\Http\Response
     */
    public function show(Contract $contract)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contract  $contract
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contract $contract)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Contract  $contract
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contract $contract)
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
