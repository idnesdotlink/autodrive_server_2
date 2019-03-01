<?php

namespace Autodrive\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    //
    public $guarded = [];

    public function regency()
    {
        return $this->hasMany(Regency::class);
    }
}
