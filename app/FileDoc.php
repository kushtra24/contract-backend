<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FileDoc extends Model
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
    protected $table = 'files';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'contract_id',
        'filename',
        'mime',
        'display_filename',
        'size',
        'deleted',
    ];

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
    protected $appends = [
        'contractId',
        'displayFilename',
    ];


    /**
     * Define relationship
     * File can belong to at least one contract
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function contract()
    {
        return $this->hasOne('App\Contract');
    }


//  _____________________________________ property accessors

    /**
     * Define snake case as camel case
     *
     * @return mixed
     */
    public function getContractIdAttribute()
    {
        if (!isset($this->attributes['contract_id'])) {
            return null;
        }
        return $this->attributes['contract_id'];
    }

    /**
     * Define snake case as camel case
     *
     * @return mixed
     */
    public function getDisplayFilenameAttribute()
    {
        if (!isset($this->attributes['display_filename'])) {
            return null;
        }
        return $this->attributes['display_filename'];
    }
}
