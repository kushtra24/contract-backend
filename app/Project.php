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
    protected $hidden = [];

    /**
     * The attributes that should be appended when json transformation is done (trick to achieve camelCase for snakeCases).
     * See at the bottom
     *
     * @var array
     */
    protected $appends = [];


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
}
