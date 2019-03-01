<?php

namespace Autodrive\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    //
    public $guarded = [];

    public function village()
    {
        return $this->hasMany(Village::class);
    }

    public function regency()
    {
        return $this->belongsTo(Regency::class);
    }
}
