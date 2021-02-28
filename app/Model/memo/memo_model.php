<?php

namespace App\Model\memo;

use App\db\memo;
use App\Model\common_model;

class memo_model extends common_model
{

    protected static function memoDao() : memo
    {
        return new memo();
    }

}
