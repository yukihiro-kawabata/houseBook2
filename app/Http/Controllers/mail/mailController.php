<?php

namespace App\Http\Controllers\mail;

use App\Http\Controllers\Controller;
use Request;

use App\Model\mail\view_mail_list_model;

/**
 * メール系のController
 */
class mailController extends Controller
{
    private static function view_mail_list_model() : view_mail_list_model
    {
        return new view_mail_list_model();
    }

    /**
     * メール一覧
     */
    public function listAction()
    {
        $request = Request::all();

        return view('script.mail.list')->with([
            "view" => self::view_mail_list_model()->view_list(),
            "request" => $request,
        ]);
    }
}
