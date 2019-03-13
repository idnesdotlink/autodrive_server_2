<?php

namespace Autodrive\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Dyrynda\Database\Support\GeneratesUuid;
use \Ramsey\Uuid\Uuid;

class Member extends Authenticatable
{
    use
    Notifiable,
    GeneratesUuid
    ;

    protected $guarded = [];
    protected $appends = ['descendants_count', 'ancestors', 'descendants'];
    protected $cast = ['member_id' => 'uuid'];

    public function uuidColumn() {
        return 'member_id';
    }

    /* public function getMemberIdAttribute($value) {
        return Uuid::uuid4()->fromBytes($value)->toString();
    } */

    /* public function setMemberIdAttribute($value) {
        return Uuid::uuid4()->fromString($value)->getBytes();
    } */

    public function user() {
        return $this->belongsTo(User::class, 'user_member_id');
    }

    public function hasCast($key, $types = null) {
        if($key === $this->uuidColumn() && $types === 'uuid') return true;
        // dd($types);
        return parent::hasCast($key, $types);
    }

    public function member_parent() {
        return $this->hasOne(Member::class, 'member_id', 'parent_member_id');
    }

    public function parent()
    {
        return $this->hasOne(Member::class, 'id', 'parent_id');
    }

    public function village() {
        return $this->belongsTo(Village::class);
    }

    public function district() {
        return $this->village()->with('district');
    }

    public function regency() {
        return $this->district()->with('regency');
    }

    public function children()
    {
        return $this->hasMany(Member::class, 'parent_id', 'id');
    }

    public function grand_children() {
        return $this->children()->with('grand_children');
    }

    public function increment_qualification() {
        $this->increment('qualification_id');
        return $this->qualification_id;
    }

    public function can_increment_qualification() {
        $db = DB::connection($this->connection);
        $id = $this->attributes['id'];
        $max = sizeof(collect(config('level')));
        $qualification_id = $this->attributes['qualification_id'];
        if ($qualification_id >= $max) return false;
        $required = config('level.' . ($qualification_id + 1) . '.requirement');
        $count = MemberQualification::get_qualification('q' . $current, $id);
        return ($count >= $required);
    }

    public function getSiblingsAttribute() {
        return $this->select('*')
            ->where('parent_id', $this->parent_id)
            ->whereNotIn('id', [$this->id])
            ->get();
    }

    public function getDescendantsAttribute() {
        $db = DB::connection($this->connection);
        $id = $this->attributes['id'];
        $table = $this->table;
        $query = '
            WITH RECURSIVE descendants AS
            (
                SELECT id, parent_id
                FROM ' . $table . '
                WHERE id="' . $id .  '"
                UNION ALL
                SELECT member.id, member.parent_id
                FROM ' . $table . ' member,
                descendants descendant
                WHERE member.parent_id = descendant.id
            ),
            data AS (
                SELECT id
                FROM descendants
                WHERE id != ' . $id . '
                ORDER BY id
            )
            SELECT *
            FROM data
        ';
        $descendants = $db->select($query);
        return new Collection($descendants);
    }

    public function getDescendantsCountAttribute() {
        $db = DB::connection($this->connection);
        $id = $this->attributes['id'];
        $table = $this->table;
        $query = '
            WITH RECURSIVE descendants AS
            (
                SELECT id, parent_id
                FROM ' . $table . '
                WHERE id="' . $id .  '"
                UNION ALL
                SELECT member.id, member.parent_id
                FROM ' . $table . ' member,
                descendants descendant
                WHERE member.parent_id = descendant.id
            ),
            get AS (
                SELECT id
                FROM descendants
                WHERE id != ' . $id . '
            )
            SELECT COUNT(id) as count
            FROM get
        ';
        $descendants = $db->select($query);
        $collection = new Collection($descendants);
        return $collection->first()->count;
    }

    function getAncestorsAttribute() {
        $db = DB::connection($this->connection);
        $id = $this->attributes['id'];
        $table = $this->table;
        $query = '
            WITH RECURSIVE ancestors AS
            (
                SELECT id, parent_id, qualification_id
                FROM ' . $table . '
                WHERE id="' . $id .  '"
                UNION
                SELECT member.id, member.parent_id, member.qualification_id
                FROM ' . $table . ' member,
                ancestors ancestor
                WHERE member.id = ancestor.parent_id
            ),
            data AS (
                SELECT *
                FROM ancestors
                WHERE id != ' . $id . '
                ORDER BY id DESC, parent_id DESC
            )
            SELECT *
            FROM data
        ';
        $ancestors = $db->select($query);
        return new Collection($ancestors);
    }
}
