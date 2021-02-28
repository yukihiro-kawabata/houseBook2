<?php

namespace App\Model\memo;

use App\Model\memo\memo_model;

class view_memo_list_model extends memo_model
{
    /**
     * 一覧で使用するデータをまとめる
     * @return array $re データ
     */ 
    public function view_list() : array
    {
        return [
            'list' => self::memoDao()->fetch_all_date(),
        ];
    }

    /**
     * 画面からメモを編集したいとリクエストがあったら
     * 該当のデータを返す
     */
    public function edit_data($param) : array
    {
        // 指定IDが無ければカラム名をkeyにした配列を返す
        if (! array_key_jug('id', $param)) {
            return self::memoDao()->get_col();
        }

        return self::memoDao()->fetch_date_by_id($param['id']);
    }
}
