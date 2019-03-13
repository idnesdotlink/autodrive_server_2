<?php

namespace Autodrive\Models;

use Illuminate\Database\Eloquent\Model;

class OutletAdmin extends Model
{
    //
    public function outlet() {
        return $this->belongsTo(Outlet::class);
    }
}
