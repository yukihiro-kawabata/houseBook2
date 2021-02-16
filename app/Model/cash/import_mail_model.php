<?php

namespace App\Model\cash;

use App\Model\cash\cashModel;
use App\Model\mail\fetch_gmail_model;

class import_mail_model extends cashModel
{
    /** id => 件名。 idはここのファイル上のみ有効 */
    const import_mail_subject = [
        0 => 'りそなデビットカードご利用およびご利用代金引落しのご連絡',
        1 => '【Times CAR SHARE】返却証'
    ];

    const devit_kamoku_id_by_pay_place = [
        'LIFE CORPORATION'       => 64, // ライフ、食費
        'CHISAN MARCHE NAGAHARA' => 64, // マルシェ、食費
        'AMAZON CO JP'           => 49, // アマゾン、日用品
        'AEON5011905134102'      => 49, // ウエルシア、日用品
        'TOKYU STORE'            => 64, // 東急ストア、食費
    ];

    private static function fetch_gmail_model() : fetch_gmail_model
    {
        return new fetch_gmail_model();
    }    

    /**
     * 新着メールを家計簿に取り込む
     */ 
    public function import_data()
    {
        $master = array_flip(self::import_mail_subject);

        foreach (self::fetch_gmail_model()->fetch_new_mail() as $n => $data) {
            $sujetct = $data['subject'];
            $body    = $data['body'];

            $target_id = array_key_exists($sujetct, $master) ? $master[$sujetct] : NULL;
            if (is_null($target_id)) {
                continue;
            }

            // $target_id = self::import_mail_subject; // のkey番号に対応している
            switch ($target_id) {
                case 0: // りそなデビッド
                    $this->import_resona($body);
                    break;
                case 1: // タイムズシェアカー
                    $this->import_times_car($body);
                    break;
                default:
            }
        }
    }

    /**
     * タイムズカー用
     */
    private function import_times_car(string $body) : void
    {
        $replace_body = remove_new_line_str($body);

        $price = preg_replace('/.*■合計金額(.*)円.*/', '$1', $replace_body);
        $place = preg_replace('/.*■ステーション(.*).*■車両.*/', '$1', $replace_body);
        $car   = preg_replace('/.*■車両(.*) （.*■予約時間.*/', '$1', $replace_body);
        $day   = preg_replace('/.*■予約時間.*([0-9]{4})\/(0[1-9]|1[0-2])\/(0[1-9]|[12][0-9]|3[01]).*■追加運転者.*/', '$1-$2-$3', $replace_body);        

        $data = [
            'name'      => 'yukihiro',
            'price'     => replace_comma_for_type_integer(remove_space_str($price)),
            'date'      => $day,
            'comment'   => remove_new_line_str(remove_space_str($place) . " $car"),
            'kamoku_id' => 74, // シェアカー
            'half_flg'  => 1, // 1:折半対象 0:対象外
        ];

        $this->post_data($data);
    }


    /**
     * りそなのデビットカード用
     */
    private function import_resona(string $body) : void
    {
        $replace_body = remove_new_line_str($body);

        $price        = preg_replace('/.*ご利用金額：(.*)円.*/', '$1', $replace_body);
        $place        = preg_replace('/.*ご利用加盟店名（※）：(.*)（※）ご利用加盟店名は英字表記となります.*/', '$1', $replace_body);
        $uniqe_number = preg_replace('/.*(承認番号：\d+).*/', '$1', $replace_body);
        $day          = preg_replace('/.*ご利用日時：([0-9]{4})\/(0[1-9]|1[0-2])\/(0[1-9]|[12][0-9]|3[01]).*/', '$1-$2-$3', $replace_body);        

        $data = [
            'name'      => 'devit',
            'price'     => replace_comma_for_type_integer($price),
            'date'      => $day,
            'comment'   => remove_new_line_str("$place $uniqe_number"),
            'kamoku_id' => array_key_exists($place, self::devit_kamoku_id_by_pay_place) ? self::devit_kamoku_id_by_pay_place[$place] : 38, // 38 = 外食
            'half_flg'  => 0,
        ];

        $this->post_data($data);
    }

    /**
     * データを家計簿に登録する
     * @param array $post_data
     */
    private function post_data(array $post_data) : void
    {
        $requier = [
            'name'      => '名前',
            'price'     => '金額',
            'date'      => '日時',
            'comment'   => '概要',
            'kamoku_id' => '勘定科目',
            'half_flg'  => '月末精算',
        ];

        foreach ($requier as $key => $value) {
            if (! array_key_exists($key, $post_data)) {
                dx("$value が見つかりません");
            }
        }

        $curl=curl_init(url('/cash/indexexecute'));
        curl_setopt($curl,CURLOPT_POST, TRUE);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post_data));
        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, FALSE);  // 
        curl_setopt($curl,CURLOPT_SSL_VERIFYHOST, FALSE);  // 
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl,CURLOPT_COOKIEJAR,      'cookie');
        curl_setopt($curl,CURLOPT_COOKIEFILE,     'tmp');
        curl_setopt($curl,CURLOPT_FOLLOWLOCATION, TRUE); // Locationヘッダを追跡
        
        $output = curl_exec($curl);
    }
}
