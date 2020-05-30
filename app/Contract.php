<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static findOrFail(int|string $id)
 */
class Contract extends Model
{
    /**
     * DB connection for model
     * @var string
     */
    protected $connection = 'mysql';

    /**
     * DB table name
     * @var string
     */
    protected $table = 'contracts';
    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = ['id', 'type_id', 'title', 'temporary', 'end_date', 'is_original', 'signed_date', 'customer_id', 'customer_number'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['type_id','end_date', 'signed_date', 'is_original', 'project_id', 'person_id', 'created_at', 'updated_at'];

    /**
     * @var array
     */
    protected $appends = ['typeId', 'endDate', 'isOriginal', 'customerNumber', 'signedDate', 'customerId', 'customerNumber'];

    /**
     * Define relationship
     * Many to Many relationship with itself
     * Contract is linked to other contracts
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function contracts()
    {
        return $this->belongsToMany('App\Contract', 'linked_contracts', 'contract_id', 'linked_contracts_id');
    }

    /**
     * Define relationship
     * Contract can have at least one file
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function file()
    {
        return $this->hasOne('App\FileDoc');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function people()
    {
        $LocalDb = env('DB_DATABASE');

        return $this->belongsToMany('App\Person', $LocalDb.'.contract_person', 'contract_id', 'person_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function projects()
    {
        $LocalDb = env('DB_DATABASE');

        return $this->belongsToMany('App\Project', $LocalDb.'.contract_project');
    }

    /**
     * Define relationship
     * Contract can have one type
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function type()
    {
        return $this->hasOne('App\Type');
    }

    /**
     * has one customer
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function customer()
    {
        return $this->hasOne('App\customer');
    }

//  _____________________________________ property accessors

    /**
     * Define snake case as camel case
     *
     * @return mixed
     */
    public function getTypeIdAttribute()
    {
        if (!isset($this->attributes['type_id'])) {
            return null;
        }
        return $this->attributes['type_id'];
    }

    /**
     * Define snake case as camel case
     *
     * @return mixed
     */
    public function getEndDateAttribute()
    {
        if (!isset($this->attributes['end_date'])) {
            return null;
        }
        return $this->attributes['end_date'];
    }

    /**
     * Define snake case as camel case
     *
     * @return mixed
     */
    public function getIsOriginalAttribute()
    {
        if (!isset($this->attributes['is_original'])) {
            return null;
        }
        return $this->attributes['is_original'];
    }

    /**
     * Define snake case as camel case
     *
     * @return mixed
     */
    public function getCustomerNumberAttribute()
    {
        if (!isset($this->attributes['customer_number'])) {
            return null;
        }
        return $this->attributes['customer_number'];
    }

    /**
     * Define snake case as camel case
     *
     * @return mixed
     */
    public function getSignedDateAttribute()
    {
        if (!isset($this->attributes['signed_date'])) {
            return null;
        }
        return $this->attributes['signed_date'];
    }


    /**
     * Define snake case as camel case
     *
     * @return mixed
     */
    public function getProjectIdAttribute()
    {
        if (!isset($this->attributes['project_id'])) {
            return null;
        }
        return $this->attributes['project_id'];
    }

    /**
     * Define snake case as camel case
     *
     * @return mixed
     */
    public function getLinkedContractsIdAttribute()
    {
        if (!isset($this->attributes['linked_contracts_id'])) {
            return null;
        }
        return $this->attributes['linked_contracts_id'];
    }

    /**
     * Define snake case as camel case
     *
     * @return mixed
     */
    public function getCustomerIdAttribute()
    {
        if (!isset($this->attributes['customer_id'])) {
            return null;
        }
        return $this->attributes['customer_id'];
    }

}
