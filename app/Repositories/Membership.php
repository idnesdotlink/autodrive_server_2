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
        $this->level = config('level');
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

    public function lev() {
        return $this->level;
    }
}
