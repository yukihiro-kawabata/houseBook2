<?php

namespace App\Model\remind;

use App\Model\common_model;

use App\db\remind;

class remind_model extends common_model
{
    const DAY_OF_WEEKS = [
        0 => '日曜',
        1 => '月曜',
        2 => '火曜',
        3 => '水曜',
        4 => '木曜',
        5 => '金曜',
        6 => '土曜',
    ];

    protected static function remindDao() : remind
    {
        return new remind();
    }
}
