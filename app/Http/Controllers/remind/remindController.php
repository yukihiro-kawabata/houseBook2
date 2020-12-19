<?php

namespace App\Http\Controllers\remind;

use App\Http\Controllers\Controller;
use Request;

use App\Model\remind\view_remind_list_model;
use App\db\remind;

/**
 * リマインド系のController
 */
class remindController extends Controller
{

    private static function remindDao() : remind
    {
        return new remind();
    }

    private static function view_remind_list_model() : view_remind_list_model
    {
        return new view_remind_list_model();
    }

    /**
     * リマンド一覧
     */
    public function listAction()
    {
        $request = Request::all();

        return view('script.remind.list')->with([
            "view" => self::view_remind_list_model()->view_list(),
            "request" => $request,
        ]);
    }

    /**
     * リマインドの新規登録
     */
    public function indexexecute()
    {
        $request = Request::all();
        $request['created_at'] = now();
        self::remindDao()->insert([0 => $request]);

        return redirect(url('/remind/list'));
    }

}
