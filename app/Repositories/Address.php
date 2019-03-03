<?php

namespace Autodrive\Repositories;

use Illuminate\Support\Facades\{DB, Storage};
use Autodrive\Models\{Province, Regency, District, Village};
use Carbon\Carbon;

class Address {

    public function __construct() {
    }

    public function seed_data($file) {
        $json = Storage::disk('area')->get($file . '.json');
        $json = json_decode($json);
        $json = collect($json);
        $columns = collect($json->pull('columns'))->merge(['created_at', 'updated_at']);
        $rows = collect($json->pull('rows'));
        $now = Carbon::now()->toDateTimeString();
        $data = $rows->map(function($row) use($columns, $now) {
            $row = collect($row)->merge([$now, $now])->all();
            return $columns->combine($row)->all();
        });
        return $data;
    }

    public function seeder($model) {
        $data = $this->seed_data($model);
        $model = 'Autodrive\\Models\\' . $model;
        if($data->count() > 1000) {
            $chunked = $data->chunk(1000);
            $chunked->each(function($group) use($model) {
                $model::insert($group->all());
            });

        } else {
            $model::insert($data->all());
        }
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
