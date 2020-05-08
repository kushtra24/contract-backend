<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    /**
     * DB connection for model
     * @var string
     */
    protected $connection = 'mysql_big_picture';
    protected $table = 'customer';
    /**
     * the attribute that are mass assignable
     * nothing since read only
     * @var array
     */
    protected $fillable = [ 'id', 'title', 'sap_id', 'center_leader_id', 'segment_leader_id', 'sales_id', 'name', 'abbreviation', 'active', 'number'];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [ 'sap_id', 'center_leader_id', 'segment_leader_id', 'sales_id' ];

    /**
     * The attributes that should be appended when json transformation is done (trick to achieve camelCase for snakeCases).
     * See at the bottom
     *
     * @var array
     */
    protected $appends = [ 'sapId', 'centerLeaderId', 'segmentLeaderId', 'salesId' ];

    /**
     * customer has many projects
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function projects() {
        return $this->hasMany('App\Project');
    }


    public function contract() {
        return $this->hasOne('App\Contract');
    }

    // ---------------------------- property accessors

    /**
     * Define snake case as camel case
     *
     * @return mixed
     */
    public function getSapIdAttribute() {
        if(!isset($this->attributes['sap_id'])) { return null; }
        return $this->attributes['sap_id'];
    }

    /**
     * Define snake case as camel case
     *
     * @return mixed
     */
    public function getCenterLeaderIdAttribute() {
        if(!isset($this->attributes['center_leader_id'])) { return null; }
        return $this->attributes['center_leader_id'];
    }

    /**
     * Define snake case as camel case
     *
     * @return mixed
     */
    public function getSegmentLeaderIdAttribute() {
        if(!isset($this->attributes['segment_leader_id'])) { return null; }
        return $this->attributes['segment_leader_id'];
    }

    /**
     * Define snake case as camel case
     *
     * @return mixed
     */
    public function getSalesIdAttribute() {
        if(!isset($this->attributes['sales_id'])) { return null; }
        return $this->attributes['sales_id'];
    }
}
