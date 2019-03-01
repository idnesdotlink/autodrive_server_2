<?php

namespace Autodrive\Models;

use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    //
    public $guarded = [];


    public function district()
    {
        return $this->belongsTo(District::class);
    }
}
