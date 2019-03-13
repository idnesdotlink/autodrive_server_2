<?php

namespace Autodrive\Models;

use Illuminate\Database\Eloquent\Model;

class Outlet extends Model
{
    //
    public function admin() {
        return $this->hasMany(OutletAdmin::class);
    }

    public function user() {
        return $this->hasMany(User::class);
    }

    public function regency() {
        return $this->belongsTo(Regency::class);
    }

    public function province() {
        return $this->belongsTo(Province::class);
    }

}
