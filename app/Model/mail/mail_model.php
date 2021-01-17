<?php

namespace App\Model\mail;

use App\Model\common_model;
use App\Model\mail\fetch_gmail_model;

use App\db\mail;

class mail_model extends common_model
{
    protected static function fetch_gmail_model() : fetch_gmail_model
    {
        return new fetch_gmail_model();
    }

}
