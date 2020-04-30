<?php

namespace App\Model\api;

use Illuminate\Database\Eloquent\Model;

class api_model extends Model
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

}
