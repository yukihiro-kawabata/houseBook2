<?php

namespace App\Http\Controllers\kamoku;

use App\Http\Controllers\commonController;

use Request;

use App\Model\kamoku\view_kamoku_list_model;

use App\db\kamoku_mst;

class kamokuController extends commonController
{

    /**
     * 科目マスター一覧
     */ 
    public function listAction()
    {
        $request = Request::all();

        $view_kamoku_list_model = new view_kamoku_list_model();
        $view = $view_kamoku_list_model->view_list($request); // mainの一覧部分
        $view_regist = $view_kamoku_list_model->view_regist_data(); // データ登録用の部分

        return view('script.kamoku.list')->with([
            "view"        => $view,
            "view_regist" => $view_regist,
        ]);
    }

    /**
     * 科目マスター新規登録処理
     */
    public function indexexecute()
    {
        $request = Request::all();

        array_key_jug('kamoku', $request, 'Not found "kamoku"');
        array_key_jug('kamoku_sum', $request, 'Not found "kamoku_sum"');
        array_key_jug('amount_flg', $request, 'Not found "amount_flg"');

        $request['updated_at'] = $request['created_at'] = now();

        $kamoku_mstDao = new kamoku_mst();
        $kamoku_mstDao->insert($request);

        // slackに通知する
        $msg = "新規科目が追加されました"                  . PHP_EOL;
        $msg .= "-------------------------------"       . PHP_EOL;
        $msg .= "末端科目 : " . $request['kamoku']       . PHP_EOL;
        $msg .= "集計科目 : " . $request['kamoku_sum']   . PHP_EOL;
        $msg .= "収入支出 : " . $request['amount_flg']   . PHP_EOL;
        $msg .= "優先度 : "   . $request['priority_flg'] . PHP_EOL;
        $this->common_model()->slack_push_msg($msg);

        return redirect(url('/kamoku/list'));
    }

}
