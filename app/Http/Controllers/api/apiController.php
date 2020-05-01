<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\commonController;

use App\Model\api\api_model;

use Request;

class apiController extends commonController
{
    public function fetch_cash_sum_kamoku_graph()
    {
        $request = Request::all();
        
        $api_model = new api_model();
        return $api_model->fetch_cash_sum_kamoku_graph($request['axis']);
    }
}
