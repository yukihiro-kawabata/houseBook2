<?php

namespace App\Model\todo2;

use App\Model\common_model;

use App\Model\slack\slack_push_model;
use App\db\todo2;

class todo2_model extends common_model
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

    const TODO_STATUS = [
        1 => '未着手',
        2 => '削除',
        9 => '完了',
    ];

    protected static function todo2Dao() : todo2
    {
        return new todo2();
    }

    protected static function slack_push_model() : slack_push_model
    {
        return new slack_push_model();
    }
}
