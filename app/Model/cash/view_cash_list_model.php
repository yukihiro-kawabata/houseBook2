<?php

namespace App\Model\cash;

use Illuminate\Database\Eloquent\Model;

use  App\Model\cash\cashModel;
use App\db\cash;

class view_cash_list_model extends cashModel
{
    private static function cashDao() : cash
    {
        return new cash();
    }

    private function fetch_pay_off_this_month($yyyymm)
    {
        return self::cashDao()->fetch_pay_off_user_each(preg_replace('/^\d{4}/', '', $yyyymm));
    }

    /*
     * 一覧で使用するデータをまとめる
     * @return array $re データ
     */ 
    public function view_list(array $param) : array
    {
        return [
            'sum_balance' => $this->sum_balance(), // 残高
            'sum_balance_target_month' => $this->sum_balance_target_month($param['date']), // 今月の収入・支出
            'sum_kamoku_list' => $this->sum_kamoku_list($param['date']), // 指定月の集計科目ごとの金額
            'sum_kamoku_month_list' => $this->sum_kamoku_list_all(), // 年月ごとの集計科目の金額
            'devit_pay' => $this->card_pay_fee((int)$param['date']), // デビットカードの使用金額
            'detail' => $this->fetch_detail((int)$param['date']), // 明細を取得する
            'pay_off' => $this->fetch_pay_off_this_month($param['date']), // 月末精算対象のサマリーデータ
        ];
    }
}
