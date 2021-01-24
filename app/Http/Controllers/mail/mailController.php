<?php

namespace App\Http\Controllers\mail;

use App\Http\Controllers\Controller;
use Request;

use App\Model\mail\view_mail_list_model;
use App\Model\cash\import_mail_model;

/**
 * メール系のController
 */
class mailController extends Controller
{
    private static function view_mail_list_model() : view_mail_list_model
    {
        return new view_mail_list_model();
    }

    private static function import_mail_model() : import_mail_model
    {
        return new import_mail_model();
    }

    /**
     * メール一覧
     */
    public function importAction()
    {
        $request = Request::all();

        return view('script.mail.import')->with([
            "view" => self::view_mail_list_model()->view_list(),
            "request" => $request,
        ]);
    }

    /**
     * 新着メールを家計簿明細に登録する
     */
    public function importexecute()
    {
        $request = Request::all();

        self::import_mail_model()->import_data();

        return redirect (url('/mail/import'));
    }

}
