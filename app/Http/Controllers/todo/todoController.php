<?php

namespace App\Http\Controllers\todo;

use App\Http\Controllers\Controller;
use Request;

use App\Model\todo\view_todo_list_model;
use App\Model\todo\update_todo_result_model;
use App\db\todo;

/**
 * リマインド系のController
 */
class todoController extends Controller
{

    private static function todoDao() : todo
    {
        return new todo();
    }

    private static function view_todo_list_model() : view_todo_list_model
    {
        return new view_todo_list_model();
    }

    private static function update_todo_result_model() : update_todo_result_model
    {
        return new update_todo_result_model();
    }

    /**
     * リマンド一覧
     */
    public function listAction()
    {
        $request = Request::all();

        return view('script.todo.list')->with([
            "view" => self::view_todo_list_model()->view_list(),
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
        self::todoDao()->insert([0 => $request]);

        return redirect(url('/todo/list'));
    }

    /**
     * todoのステータス変更
     */
    public function resultUpdateexecute()
    {
        $request = Request::all();
        self::update_todo_result_model()->update_todo_result($request);

        return redirect(url('/todo/list'));
    }

}
