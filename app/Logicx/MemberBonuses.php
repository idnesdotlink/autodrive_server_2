<?php
declare(strict_types=1);

namespace Autodrive\Logic;
use Illuminate\Support\Facades\{DB, Storage};
use Illuminate\Support\Collection;
use Autodrive\Logic\{Levels, MemberQualification};

class MemberBonuses {
    public static function create_table(&$db) {
        $db->statement('
            CREATE TABLE member_bonuses (
                memberId MEDIUMINT UNSIGNED DEFAULT NULL,
                type VARCHAR(128) NOT NULL DEFAULT \'\',
                amount DOUBLE UNSIGNED NOT NULL DEFAULT 0,
                created DATETIME DEFAULT NOW(),
                paidAt DATETIME DEFAULT NULL
            )
        ');
    }

    /**
     *
     *
     * @param [type] $db
     * @return void
     */
    public static function drop_table(&$db): void {
        $db->statement('DROP TABLE IF EXISTS member_bonuses');
    }
}
