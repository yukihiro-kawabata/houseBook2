<?php

namespace App\Model\todo;

use App\Model\todo\todo_model;

class register_todo_model extends todo_model
{
    /**
     * データをToDoに登録する
     * @param array $post_data
     */
    public function post_data(array $post_data) : void
    {
        $requier = [
            'title' => 'タイトル',
            'text'  => '内容',
            'day'   => '日付',
            'week'  => '曜日',
            'time'  => '時間',
        ];

        foreach ($requier as $key => $value) {
            if (! array_key_exists($key, $post_data)) {
                dx("$value が見つかりません");
            }
        }

        $curl=curl_init(url('/todo/indexexecute'));
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