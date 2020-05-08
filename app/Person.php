<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    /**
     * DB connection for model
     * @var string
     */
    protected $connection = 'mysql_hr_public';

    /**
     * DB table name
     * @var string
     */
    protected $table = 'people';


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
    protected $hidden = [ 'last_name', 'first_name', 'mail', 'created_at', 'updated_at' ];

    /**
     * The attributes that should be appended when json transformation is done (trick to achieve camelCase for snakeCases).
     * See at the bottom
     *
     * @var array
     */
    protected $appends = [ 'lastName', 'firstName' ];

    /**
     * Define relationship
     * Person has at least one (tool) user
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function user() {
        return $this->hasMany('App\User');
    }

    /**
     * Define relationship
     * Person has many Contracts
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function contract() {
        $LocalDb = env('DB_DATABASE');
        return $this->belongsToMany('App\Contract', '.contract_person','contract_id', 'person_id');
    }


    // ---------------------------- property accessors

    /**
     * Define snake case as camel case
     *
     * @return mixed
     */
    public function getFirstNameAttribute() {
        if(!isset($this->attributes['first_name'])) { return null; }
        return $this->attributes['first_name'];
    }

    /**
     * Define snake case as camel case
     *
     * @return mixed
     */
    public function getLastNameAttribute() {
        if(!isset($this->attributes['last_name'])) { return null; }
        return $this->attributes['last_name'];
    }

}
