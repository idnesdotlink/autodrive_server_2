<?php

namespace Autodrive\Repositories;

use Illuminate\Support\Facades\{DB, Storage};
use Autodrive\Models\{Province, Regency, District, Village};

class Address {

    public static function seeder($model) {
        $json = Storage::disk('area')->get($model . '.json');
        $json = json_decode($json);
        $json = collect($json);
        $columns = collect($json->pull('columns'));
        $rows = collect($json->pull('rows'));
        $data = $rows->map(function($row) use($columns) {
            return $columns->combine($row)->all();
        });
        $data = $data->each(function($row) use($model) {
            $model = 'Autodrive\\Models\\' . $model;
            $model::create($row);
            // Province::create($row);
        });
    }

    public static function all_code_from_village_code($village_code) {
        $village = Village::with('district', 'district.regency', 'district.regency.province');
        $village = $village->where('id', $village_code);
        $village = $village->get()->first();
        $district = $village->district->first();
        $regency = $district->regency->first();
        $province = $regency->province->first();
        return [
            $village->id,
            $district->id,
            $regency->id,
            $province->id,
        ];
    }
}
