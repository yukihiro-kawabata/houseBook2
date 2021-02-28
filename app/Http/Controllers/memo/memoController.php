<?php

namespace App\Http\Controllers\memo;

use App\Http\Controllers\Controller;
use Request;

use App\db\memo;
use App\Model\memo\view_memo_list_model;

/**
 * メール系のController
 */
class memoController extends Controller
{

    private static function memoDao() : memo
    {
        return new memo();
    }

    private static function view_memo_list_model() : view_memo_list_model
    {
        return new view_memo_list_model();
    }

    /**
     * メモ登録処理
     */
    public function indexexecute()
    {
        $request = Request::all();
        if (empty($request['id'])) {
            self::memoDao()->insert([
                0 => (
                    $request + ['created_at' => now()]
                )]);
        } else {
            self::memoDao()
                ->where('id', $request['id'])
                ->update($request);
        }
        return redirect(url('/memo/list'));
    }

    /**
     * メモ一覧
     */
    public function listAction()
    {
        $request = Request::all();

        return view('script.memo.list')->with([
            'view' => self::view_memo_list_model()->view_list(),
            'edit' => self::view_memo_list_model()->edit_data($request),
        ]);
    }

}
