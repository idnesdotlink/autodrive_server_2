<?php

namespace Autodrive\Repositories;

use Illuminate\Support\Facades\{DB, Storage};
use Autodrive\Models\{Member};

class Membership {

    public $member;
    public $db;
    public $level;

    public function __construct(Member $member, DB $db) {
        $this->member = $member;
        $this->db = $db;
        $this->level = collect(config('level'));
    }

    public function test() {
        return $this->member;
    }

    public function add() {
        $member = $this->member;
        $function = function () use($member) {
            try {
                $id = $member->insertGetId(
                    ['qualification_id' => 3, 'parent_id' => null, 'name' => 'hello']
                );
                return $id;
            } catch(\Exception $error) {
                return $error;
            }
        };

        $transaction = $this->db::transaction($function, 5);
        return $transaction;
    }

    public function generate_seed(int $max_level) {
        if ($max_level > sizeof($this->level)) throw new \Exception('Bigger than maximum level');
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
