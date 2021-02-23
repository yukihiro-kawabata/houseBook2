<?php

namespace App\Model\todo;

use App\Model\common_model;

use App\db\todo;
use App\db\todo_result;

class todo_model extends common_model
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

    const TODO_RESULT_STATUS = [
        1 => '未着手',
        2 => '削除',
        9 => '完了',
    ];

    protected static function todoDao() : todo
    {
        return new todo();
    }

    protected static function todo_resultDao() : todo_result
    {
        return new todo_result();
    }
}
