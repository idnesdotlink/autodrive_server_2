<?php

namespace Autodrive\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class MemberQualifications extends Model
{
    //
    public $table = 'member_qualifications';

    public function get_all_qualification($member_id) {
        $table = $this->table;
        $query = '
            SELECT *
            FROM ' . $table . '
            WHERE member_id = ' . $member_id . '
        ';
        $stat = $db->select($query);
        return new Collection($stat);
    }

    /**
     * Undocumented function
     *
     * @param integer $member_id
     * @param string $key
     * @param object $db
     * @return integer
     */
    public function get_qualification(string $key, int $member_id = null) {
        $table = $this->table;
        $sql = '
            SELECT *
            FROM ' . $table . '
            WHERE member_id = ' . $member_id . ' AND
            name = \'' . $key . '\'
        ';
        $stat = $db->select($sql);
        $stat = new Collection($stat);
        if ($stat->count() < 1) return 0;
        return $stat->first()->value;
    }

    /**
     * Undocumented function
     *
     * @param int $member_id
     * @param string $key
     * @param object $db
     * @return int
     */
    public function increment(int $member_id, string $key, int $q = null) {
        $table = $this->table;
        $q = ($q === null) ? 0 : $q;
        $sql = '
            INSERT INTO ' . $table . '
            (member_id, name, value, qualification)
            VALUES
            (' . $member_id . ', \'' . $key . '\', 1, NULL)
            ON DUPLICATE KEY UPDATE
            member_id = ' . $member_id . ',
            name = \'' . $key . '\',
            value = value + 1,
            qualification = ' . $q . '
        ';
        $db->insert($sql);
        $sql = '
            SELECT value
            FROM ' . $table . '
            WHERE member_id = ' . $member_id . ' AND
            name = \'' . $key . '\'
        ';
        $value = $db->select($sql);
        $collection = new Collection($value);
        return $collection->first()->value;
    }

    /**
     * Undocumented function
     *
     * @param int $member_id
     * @param string $key
     * @param object $db
     * @return int
     */
    public function decrement(int $member_id, string $key, int $q = null) {
        $table = $this->table;
        $sql = '
            INSERT INTO ' . $table . '
            (member_id, name, value, qualification)
            VALUES
            (' . $member_id . ', \'' . $key . '\', 1, NULL)
            ON DUPLICATE KEY UPDATE
            member_id = ' . $member_id . ',
            name = \'' . $key . '\',
            value = value - 1,
            qualification = ' . $q . '
        ';
        $db->insert($sql);
        $sql = '
            SELECT value
            FROM ' . $table . '
            WHERE member_id = ' . $member_id . ' AND
            name = \'' . $key . '\'
        ';
        $value = $db->select($sql);
        $collection = new Collection($value);
        return $collection->first()->value;
    }
}
