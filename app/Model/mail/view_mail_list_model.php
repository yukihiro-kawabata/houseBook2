<?php

namespace App\Model\mail;

use App\Model\mail\mail_model;

class view_mail_list_model extends mail_model
{
    /**
     * 一覧で使用するデータをまとめる
     * @return array $re データ
     */ 
    public function view_list() : array
    {
        return [];
    }
}
