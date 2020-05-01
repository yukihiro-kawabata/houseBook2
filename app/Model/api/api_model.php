<?php

namespace App\Model\api;

use App\Model\common_model;

use App\Model\cash\cashModel;

class api_model extends common_model
{
    
    /*
     * POST送信
     */ 
    public function post_request_send($url, array $data = [])
    {
        $context = [
            'http' => [
                'method'  => 'POST',
                'header'  => implode("\r\n", array('Content-Type: application/x-www-form-urlencoded',)),
                'content' => http_build_query($data)
            ]
        ];
    
        return file_get_contents($url, false, stream_context_create($context));            
    }


    /**
     * cash一覧で使用するグラフデータを用意する
     * @param string $axis x/[集計科目名称] ex.) x . ex). 水道光熱費
     */
    public function fetch_cash_sum_kamoku_graph(string $axis)
    {
        $re = [];

        $cashModel = new cashModel();
        $sum_kamoku_all_data = $cashModel->sum_kamoku_list_all();
        foreach ($sum_kamoku_all_data as $sum_kamoku => $data) {
            foreach ($data as $month => $price) {
                if ($axis === 'x') $re[] = $month;
                if ($axis === $sum_kamoku) $re[] = $price;
            }
            if (!empty($re)) break;
        }
        return $re;
    }

}
