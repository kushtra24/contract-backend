<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Migrate values of an given item (associative array) according to given property array
     *
     * @param $item
     * @param $propArr ($key: property which should be migrated to target property, $value: target property name )
     * @return mixed
     */
    protected  function migrateProperty($item, $propArr) {
        if(!is_countable($item) || !is_countable($propArr)) { return $item; }

        // migrate values
        foreach ($propArr as $key => $value) {

            if(array_key_exists($key, $item) && !isset($item[$value])) {
                $item[$value] = $item[$key];
            }
            unset($item[$key]);
        }
        return $item;
    }

    /**
     * Validate given data with given rules
     *
     * @param $data
     * @param $rules
     * @param bool $canBeEmpty (boolean) Flag to determine if empty $data should not be validated
     * @param null $attributeNames which can be set in error messages
     * @throws ValidationException
     */
    protected function validateData(&$data, $rules, $canBeEmpty = false, $attributeNames = null) {
        if(!isset($data) || !count($data)) {
            if($canBeEmpty) { return; }
            throw new HttpException(400, 'Error for validation! No data given for validation or parts are missing');
        }

        $validator = Validator::make($data, $rules);
        //  set attribute names
        if(isset($attributeNames)) {
            $validator->setAttributeNames($attributeNames);
        }

        if($validator->fails()) {
            throw new ValidationException($validator, 422);
        }
    }

    //----------------- HELPER
    /**
     * Clean array by given $removeValue
     * and simple trim values if possible
     *
     * @param $array
     * @param null $removeValue
     * @param null $exceptionsArr
     * @return array
     */
    protected function cleanArray($array, $removeValue = null, $exceptionsArr = null){
        if(!is_countable($array)) { return $array; }

        if(is_null($removeValue)) {
            return array_filter($array, function($value, $key) use(&$array, $exceptionsArr) {
                if(gettype($value) === 'string') {
                    // trim value
                    $array[$key] = trim($value);
                }
                //check if key is in exception
                if(!is_null($exceptionsArr) && array_search($key, $exceptionsArr) !== false) { return true; }
                // check if value is not NULL and set and not an empty string
                return !(is_null($value) || !isset($value) || (gettype($value) === 'string' && trim($value) === ''));

            }, ARRAY_FILTER_USE_BOTH);
        }

        return array_filter($array, function($value, $key) use (&$array, $removeValue, $exceptionsArr) {
            if(gettype($value) === 'string') {
                // trim value
                $array[$key] = trim($value);
            }
            //check if key is in exception
            if(!is_null($exceptionsArr) && array_search($key, $exceptionsArr) !== false) { return true; } // in exception
            // check if value is NOT remove value
            return $value !== $removeValue;

        }, ARRAY_FILTER_USE_BOTH);
    }

    /**
     * Execute given query by considering all given parameters
     * @param null $query
     * @param bool $page
     * @param null $limit
     * @param null $orderByArr
     * @param string $orderType
     * @return mixed
     */
    protected function executeQuery(&$query = null, $page = null, $limit = null, $orderByArr = null, $orderType = 'asc') {
        $result = null;
        if(!isset($query)) { return $result; }

//    Log::info('HERE: ' . var_export($query, true));

        // order by array
        if(is_countable($orderByArr) && count($orderByArr) > 0) {
            // check sort ranking
            if(!isset($orderType) || $orderType !== 'desc' && $orderType !== 'asc') {
                $orderType = 'asc';
            }

            // create order by
            for($i = 0, $max = count($orderByArr); $i < $max; $i++) {
                $attr = $orderByArr[$i];
                if(!isset($attr)) { continue; }

                $query = $query->orderBy($attr, $orderType);
            }
        }

        // check for pagination
        if(isset($page) && $page > 0) {
            // check limit
            if(!isset($limit) || $limit <= 0) { $limit = 10; }
            // execute
            // $result = $query->paginate($limit); // laravel doing pagination (slow)
            $result = $this->paginate($query, $page, $limit); //

        } else {
            // check for limit
            if(isset($limit) && $limit > 0) {
                $query = $query->limit($limit);
            }
            $result = $query->get();
        }

        return $result;
    }
}
