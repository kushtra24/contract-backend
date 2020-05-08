<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    /**
     * DB connection for model
     * @var string
     */
    protected $connection = 'mysql_big_picture';

    /**
     * The attributes that are mass assignable.
     * Nothing since read only
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [ 'procalc_id', 'segment_lead', 'project_lead', 'deputy_project_lead', 'created_at', 'updated_at' ];

    /**
     * The attributes that should be appended when json transformation is done (trick to achieve camelCase for snakeCases).
     * See at the bottom
     *
     * @var array
     */
    protected $appends = [ 'procalcId', 'segmentLead', 'projectLead', 'deputyProjectLead' ];


    public function Customer() {
        return $this->belongsTo('App\Customer');
    }

    /**
     * Define relationship
     * project may have one deputy project lead (person)
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function deputyProjectLead() {
        return $this->belongsTo('App\Person', 'deputy_project_lead');
    }

    /**
     * Define relationship
     * project has one project lead (person)
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function projectLead() {
        return $this->belongsTo('App\Person', 'project_lead');
    }

    /**
     * Define relationship
     * project has one sales person
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function salesPerson() {
        return $this->belongsTo('App\Person', 'sales');
    }

    /**
     * Define relationship
     * project has one segment lead (person)
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function segmentLead() {
        return $this->belongsTo('App\Person', 'segment_lead');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function contract() {
        $LocalDb = env('DB_DATABASE');
        return $this->belongsToMany('App\Contract', $LocalDb.'.contract_project');
    }



    // ---------------------------- property accessors

    /**
     * Define snake case as camel case
     *
     * @return mixed
     */
    public function getProcalcIdAttribute() {
        if(!isset($this->attributes['procalc_id'])) { return null; }
        return $this->attributes['procalc_id'];
    }

    /**
     * Define snake case as camel case
     *
     * @return mixed
     */
    public function getSegmentLeadAttribute() {
        if(!isset($this->attributes['segment_lead'])) { return null; }
        return $this->attributes['segment_lead'];
    }

    /**
     * Define snake case as camel case
     *
     * @return mixed
     */
    public function getProjectLeadAttribute() {
        if(!isset($this->attributes['project_lead'])) { return null; }
        return $this->attributes['project_lead'];
    }

    /**
     * Define snake case as camel case
     *
     * @return mixed
     */
    public function getDeputyProjectLeadAttribute() {
        if(!isset($this->attributes['deputy_project_lead'])) { return null; }
        return $this->attributes['deputy_project_lead'];
    }
}
