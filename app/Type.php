<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static find($type_id)
 */
class Type extends Model
{
    /**
     * The attributes that are mass assignable.
     * Nothing since read only
     *
     * @var array
     */
    protected $fillable = [''];


    /**
     * Define relationship
     * Type has one Contract
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function contract() {
        return $this->hasOne('App\Contract');
    }
}
