<?php
declare(strict_types=1);

namespace Autodrive\Logic;
use Illuminate\Support\Facades\{DB, Storage};
use Illuminate\Support\Collection;
use Autodrive\Logic\{Levels, MemberQualification, HasTableInterface};
use Illuminate\Database\ConnectionInterface;

class Regencies implements HasTableInterface {

    /**
     * Undocumented function
     *
     * @param ConnectionInterface $db
     * @return void
     */
    public static function create_table(ConnectionInterface &$db = null): void {
        $db = ($db === null) ? DB::connection(self::$db_connection) : $db;
        $sql = '
            CREATE TABLE regencies (
                provinceId CHAR(2),
                id CHAR(4),
                name VARCHAR(128) NOT NULL DEFAULT \'\',
                UNIQUE KEY regency (id),
                INDEX province (provinceId, id)
            )
        ';
        $db->statement($sql);
    }

    /**
     * Undocumented function
     *
     * @param ConnectionInterface $db
     * @return void
     */
    public static function drop_table(ConnectionInterface &$db = null): void {
        $db = ($db === null) ? DB::connection(self::$db_connection) : $db;
        $sql = 'DROP TABLE IF EXISTS regencies';
        $db->statement($sql);
    }

    public static function get_all() {
        return
            [
                [
                    'value' => 1,
                    'name' => 'test name'
                ]
            ]
        ;
    }

    public static function get_one() {

    }

}
