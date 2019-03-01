<?php

namespace Autodrive\Models;

use Illuminate\Database\Eloquent\Model;

class Regency extends Model
{
    //
    public $guarded = [];

    public function district()
    {
        return $this->hasMany(District::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }
}
