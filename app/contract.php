<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class contract extends Model
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
    protected $fillable = ['type_id', 'title', 'temporary', 'end_date', 'original_at_team_assistant', 'rating', 'rating_bg', 'submitting_person_id', 'customer_number', 'signed_date', 'customer_id', 'supplier_id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['type_id','end_date', 'signed_date', 'original_at_team_assistant', 'submitting_person_id', 'project_id', 'person_id', 'created_at', 'updated_at', 'supplier_id'];

    /**
     * @var array
     */
    protected $appends = ['typeId', 'endDate', 'originalAtTeamAssistant', 'ratingBg', 'customerNumber', 'signedDate', 'segmentId', 'submittingPersonId','customerId', 'supplierId'];

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
        return $this->hasOne('App\File');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function persons()
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

    /**
     * has one supplier
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function supplier() {
        return $this->hasOne('App\supplier');
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
    public function getOriginalAtTeamAssistantAttribute()
    {
        if (!isset($this->attributes['original_at_team_assistant'])) {
            return null;
        }
        return $this->attributes['original_at_team_assistant'];
    }

    /**
     * Define snake case as camel case
     *
     * @return mixed
     */
    public function getRatingBgAttribute()
    {
        if (!isset($this->attributes['rating_bg'])) {
            return null;
        }
        return $this->attributes['rating_bg'];
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
    public function getSegmentIdAttribute()
    {
        if (!isset($this->attributes['segment_id'])) {
            return null;
        }
        return $this->attributes['segment_id'];
    }

    /**
     * Define snake case as camel case
     *
     * @return mixed
     */
    public function getSubmittingPersonIdAttribute()
    {
        if (!isset($this->attributes['submitting_person_id'])) {
            return null;
        }
        return $this->attributes['submitting_person_id'];
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
