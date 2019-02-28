<?php

namespace Autodrive\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Member extends Authenticatable
{
    use Notifiable;
    // protected $fillable = ['id', 'parent_id', 'name', 'qualification_id', 'level_id'];
    protected $guarded = [];

    protected $appends = ['descendants_count, ancestors, descendants'];

    public function parent()
    {
        return $this->hasOne(Member::class, 'id', 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Member::class, 'parent_id', 'id');
    }

    public function member_qualification() {
        return $this->hasMany(MemberQualification::class, 'parent_id', 'id');
    }

    public function query_increment_qualification() {
        $db = DB::connection($this->connection);
        $id = $this->attributes['id'];
        $query = '
            UPDATE members
            SET qualification = qualification + 1
            WHERE id = ' . $id . '
        ';
        $db->update($query);
        $query = '
            SELECT qualification
            FROM members
            WHERE id = ' . $id . '
        ';
        $qualification = new Collection($db->select($query));
        $qualification = $qualification->first()->qualification;
        $return = new Collection([
            'from' => $qualification - 1, 'to' => $qualification
        ]);
        return $return;
    }

    public function can_increment_qualification() {
        $db = DB::connection($this->connection);
        $id = $this->attributes['id'];
        $max = 9;
        $current = $this->attributes['qualification'];
        if ($current >= $max) return false;
        $next = $current + 1;
        $required = config('level.' . $next . '.requirement');
        $count = MemberQualification::get_qualification('q' . $current, $id);
        return ($count >= $required);
    }

    public function add_root() {

    }

    public function getSiblingsAttribute() {
        $db = DB::connection($this->connection);
        $id = $this->attributes['id'];
        $table = $this->table;
        $parent = '
            SELECT parent_id FROM ' . $table . '
            WHERE id = ' . $id . '
            LIMIT 1
        ';
        $parent = new Collection($db->select($parent));
        $parent_id = $parent->first()->parent_id;
        if ($parent->isEmpty() || $parent_id === NULL) return new Collection([]);
        $siblings = '
            SELECT * FROM ' . $table . '
            WHERE parent_id = ' . $parent_id . '
            AND id != ' . $id . '
        ';
        $siblings = new Collection($db->select($siblings));
        return $siblings;
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
