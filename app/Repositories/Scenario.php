<?php

namespace Autodrive\Repositories;

class Scenario {

    private $level;
    private $maximum;

    public function __construct() {
        $level = config('level');
        $this->level = collect($level);
        $this->maximum = sizeof($this->level);
    }

    public function level() {
        return $this->level;
    }

    public function maximum() {
        return $this->maximum;
    }

    public function generate(int $max_level) {
        if ($max_level > $this->maximum) throw new \Exception('Bigger than maximum level');
        $level = $this->level->take($max_level)->reverse();
        $create_scenario  = function ($accumulator, $level) {
            $ids          = $accumulator['ids'];
            $nextStart    = $accumulator['ids'];
            $nextEnd      = $accumulator['ids'];
            $data         = $accumulator['data'];
            $parent_start = $accumulator['parent_start'];
            $parent_end   = $accumulator['parent_end'];
            $siblings     = $accumulator['siblings'];

            for($parent_id = $parent_start; $parent_id <= $parent_end; $parent_id++) {
                for($sibling = 1; $sibling <= $siblings; $sibling++) {
                    $data->push([
                        $ids,
                        ($parent_id === 0) ? null : $parent_id,
                        $level['id']
                    ]);
                    $ids++;
                    $nextEnd++;
                }
            }
            return [
                'ids' => $ids,
                'parent_start' => $nextStart,
                'parent_end' => $nextEnd - 1,
                'siblings' => $level['requirement'],
                'data' => $data
            ];
        };
        $data = $level->reduce($create_scenario, [
            'ids' => 1,
            'parent_start' => 0,
            'parent_end' => 0,
            'siblings' => 1,
            'data' => collect([])
        ]);
        $data = $data['data']->all();
        return [
            'columns' => ['id', 'parent_id', 'level'],
            'rows' => $data
        ];
    }
}