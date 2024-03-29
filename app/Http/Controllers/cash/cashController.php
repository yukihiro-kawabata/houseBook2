<?php

namespace App\Http\Controllers\cash;

use App\Http\Controllers\commonController;

use Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\Config;

use App\db\cash;
use App\db\constant_cash;
use App\db\kamoku_mst;

use App\Model\cash\cashModel;
use App\Model\cash\view_cash_list_model;
use App\Model\cash\view_cash_constant_list_model;

use App\Model\slack\slack_push_model;

class cashController extends commonController
{
    /*
     * 新規登録画面 or 定期的に入れるcashデータを登録する
     * 何も無ければ、新規登録画面
     */ 
    public function indexAction()
    {
        $request = Request::all();

        $kamoku_mstDao = new kamoku_mst();
        $kamokuDatas = $kamoku_mstDao->orderBy('priority_flg', 'DESC')->get();

        $cashModel = new cashModel();
        
        // true: 定期的にデータを入れるほうを登録, false: 新規登録
        if (!array_key_exists('constant', $request)) $request['constant'] = false;

        return view('script.cash.index')->with([
            "userDatas"   => Config::get('cash_const.user_name'),
            "kamokuDatas" => $kamokuDatas,
            "request"     => $request,
        ]);
    }

    public function indexexecute()
    {
        $request = Request::all();

    	$array = [
            'name'      => '名前',
            'price'     => '金額',
            'date'      => '日時',
            'comment'   => '概要',
            'kamoku_id' => '勘定科目',
            'half_flg'  => '月末精算',
        ];

        // 自動登録設定なら
        if (!empty($request['constant'])) {
            $cashDao = new constant_cash();
        } else {
            $cashDao = new cash(); // 新規登録なら
            $cashModel = new cashModel();
        }

        // 入力値をチェック
        foreach ($array as $col => $val) {
            if (!empty($request['constant']) && $col === 'date') continue; // 自動登録設定のときは、dateカラムは無視
            if (empty($request[$col]) && !in_array($col, ['half_flg'], true)) {
                dx($val . 'が足りません');
            }
            // データ登録準備
            $cashDao->{$col} = $request[$col];
        }

        // tagに勘定科目を入れる
        $kamoku_mstDao = new kamoku_mst();
        $tag = $kamoku_mstDao->where('kamoku_id', $request['kamoku_id'])->get()->first();
        $cashDao->tag = $tag['kamoku'];

        // データ登録
        $cashDao->save();
        $cashId = $cashDao->id;

        // 新規登録なら
        if (empty($request['constant'])) {
            $half_flg_msg = !empty($request['half_flg']) ? "月末精算対象です" : "";
            // slackに通知
            $msg  = "1件データが追加されました。". $half_flg_msg . PHP_EOL ;
            $msg .= "------------------------------------" . PHP_EOL;
            $msg .= "対象者：" . $request['name']    . PHP_EOL;
            $msg .= "金額　：" . number_format((int)$request['price']) . PHP_EOL;
            $msg .= "科目　；" . $tag['kamoku']      . PHP_EOL;
            $msg .= "概要　：" . $request['comment'] . PHP_EOL;

            $this->common_model()->slack_push_msg($msg);

            // デビット使用限度額までの金額を通知する
            if ($request['name'] === $cashModel::DEVIT_NAME) {
                $msg1  = "◆デビットの使用限度額まで：". number_format($cashModel->fetch_remain_devit_amount()) . PHP_EOL;
                $this->common_model()->slack_push_msg($msg1);
            }

            return redirect(url('/cash/list') . '?id=' . $cashId);
        } else {
            // 自動登録設定なら
            return redirect(url('/cash/list'));
        }
        
    }


    /*
     * 一覧画面
     */ 
    public function listAction()
    {
        $request = Request::all();

        if (!array_key_exists('date', $request)) $request['date'] = date('Ym');

        $cashModel = new cashModel();
        $view_cash_list_model = new view_cash_list_model();
    
        $view = $view_cash_list_model->view_list($request);

        return view('script.cash.list')->with([
            "view" => $view,
            "request" => $request,
            "userDatas" => array_merge(['ALL'], Config::get('cash_const.user_name')),
        ]);
    }

    /** 更新処理 */
    public function updateexecute()
    {
        $request = Request::all();

        $kamoku_mstDao = new kamoku_mst();
        $kamoku = $kamoku_mstDao->where('kamoku_id', $request['subject'])->first();

        $cachDao = new cash();
        $cachDao
            ->where('id', $request['id'])
            ->update([
                'name'      => $request['name'],
                'price'     => str_replace(',', '', $request['price']),
                'kamoku_id' => $request['subject'],
                'tag'       => $kamoku->kamoku,
                'priceFlg'  => $kamoku->amount_flg,
                'date'      => $request['date'],
                'comment'   => $request['comment'],
            ]);

            // slackに通知
            $msg  = "データ更新". PHP_EOL ;
            $msg .= "------------------------------------" . PHP_EOL;
            $msg .= "対象者：" . $request['name']    . PHP_EOL;
            $msg .= "金額　：" . $request['price']   . PHP_EOL;
            $msg .= "科目　；" . $kamoku->kamoku     . PHP_EOL;
            $msg .= "概要　：" . $request['comment'] . PHP_EOL;

            $this->common_model()->slack_push_msg($msg);

            // デビット使用限度額までの金額を通知する
            $cashModel = new cashModel();
            if ($request['name'] === $cashModel::DEVIT_NAME) {
                $msg1  = "◆デビットの使用限度額まで：". number_format($cashModel->fetch_remain_devit_amount()) . PHP_EOL;
                $this->common_model()->slack_push_msg($msg1);
            }

        return redirect($_SERVER['HTTP_REFERER']);
    }

    /*
     * 明細の削除処理
     */ 
    public function deleteexecute()
    {
        $request = Request::all();

        if (empty($request['id'])) return 'idの取得に失敗しました';

        $cashDao = new cash();
        $cashDao->where('id', $request['id'])->update(['delete_flg' => 1]);

        // 削除したデータを取得
        $data = $cashDao->where('id', $request['id'])->get()->first();
        $data['price'] = number_format((int)$data['price']);

        // 科目名称を取得
        $kamoku_mstDao = new kamoku_mst();
        $tag = $kamoku_mstDao->where('kamoku_id', $data['kamoku_id'])->get()->first();
        $cashDao->tag = $tag['kamoku'];

        // slackに通知
        $msg  = "1件データが削除されました" . PHP_EOL;
        $msg .= "------------------------------------" . PHP_EOL;
        $msg .= "対象者：" . $data['name']    . PHP_EOL;
        $msg .= "金額　：" . $data['price']   . PHP_EOL;
        $msg .= "科目　；" . $tag['kamoku']  . PHP_EOL;
        $msg .= "概要　：" . $data['comment'] . PHP_EOL;
        
        $this->common_model()->slack_push_msg($msg);

        return $data;
    }

    /*
     * IDを元に明細情報を取得する
     */ 
    public function fetch_detail_by_id()
    {
        $request = Request::all();
        if (empty($request['id'])) return 'idの取得に失敗しました';
        $cashDao = new cash();
        return $cashDao->where('id', $request['id'])->get()->first();
    }

    /*
     * 自動登録設定の一覧
     */ 
    public function constantListAction()
    {
        $request = Request::all();

        $view_cash_constant_list_model = new view_cash_constant_list_model();
        $view = $view_cash_constant_list_model->view_list();

        return view('script.cash.constant_list')->with([
            "view" => $view,
        ]);
    }

    /*
     * 自動登録設定のデータ削除
     */ 
    public function constantDeleteexecute()
    {
        $request = Request::all();
        if (empty($request['id'])) return '指定IDが見つかりません';

        $constant_cashDao = new constant_cash();
        $constant_cashDao->where('id', $request['id'])->delete();

        return "delete success!!";
    }

}
