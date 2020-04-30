<?php

namespace App\Model\cash;

use Illuminate\Database\Eloquent\Model;

use  App\Model\cash\cashModel;

class view_cash_list_model extends cashModel
{
    /*
     * 一覧で使用するデータをまとめる
     * @return array $re データ
     */ 
    public function view_list(array $param) : array
    {
        // 残高
        $re['sum_balance'] = $this->sum_balance();
        // 指定月の集計科目ごとの金額
        $re['sum_kamoku_list'] = $this->sum_kamoku_list($param['date']);
        // デビットカードの使用金額（15日締め）
        $re['devit_pay'] = $this->card_pay_fee((int)$param['date']);
        // 明細を取得する
        $re['detail'] = $this->fetch_detail((int)$param['date']);

        return $re;
    }
}
