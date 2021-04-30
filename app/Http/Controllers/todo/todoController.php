<?php

namespace App\Http\Controllers\todo;

use App\Http\Controllers\Controller;
use Request;

use App\Model\slack\slack_push_model;
use App\Model\todo\view_todo_list_model;
use App\Model\todo\update_todo_result_model;
use App\db\todo;

use App\Model\todo2\view\view_todo2_list_model;
use App\Model\todo2\register\register_todo2_model;
use App\Model\todo2\update\update_todo2_model;

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

    private static function slack_push_model() : slack_push_model
    {
        return new slack_push_model();
    }

    private static function register_todo2_model() : register_todo2_model
    {
        return new register_todo2_model();
    }

    private static function view_todo2_list_model() : view_todo2_list_model
    {
        return new view_todo2_list_model();
    }

    private static function update_todo2_model() : update_todo2_model
    {
        return new update_todo2_model();
    }

    /**
     * リマンド一覧
     */
    public function listAction()
    {
        $request = Request::all();

        return view('script.todo.list')->with([
            "view" => self::view_todo2_list_model()->view_list(),
            "request" => $request,
        ]);
    }

    /**
     * リマインドの新規登録
     */
    public function indexexecute()
    {
        self::register_todo2_model()->register_data($request = Request::all());
        return redirect(url('/todo/list'));
    }

    /**
     * todoのステータス変更
     */
    public function resultUpdateexecute()
    {
        self::update_todo2_model()->update_todo_result($request = Request::all());

        return redirect(url('/todo/list'));
    }

}
