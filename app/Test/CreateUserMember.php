<?php

namespace Autodrive\Test;

use Autodrive\Models\Member;
use Autodrive\Models\User;
use Illuminate\Support\Facades\Storage;

class CreateUserMember {

    public $member;
    public $user;
    public $storage;

    public function __construct(Member $member, User $user, Storage $storage) {
        $this->member = $member;
        $this->user = $user;
        $this->storage = $storage;
    }

    public function create_user() {
        // print_r($this->user->find(1)->name);
        $disk = $this->storage::disk('coba');
        $disk->get('test2.json');
        // $data = json_decode($disk->get('test2.json'));
        // return collect($data->rows);
    }

}
