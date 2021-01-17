<?php

namespace App\Model\mail;

use App\Model\mail\mail_model;
use Illuminate\Support\Facades\Config;

class fetch_gmail_model extends mail_model
{
    const MAILBOX = '{imap.gmail.com:993/imap/ssl}';

    /** 一度に大量のメールを取得しないように1回あたりの上限を設ける */
    const GET_MAIL_LIMIT = 100;

    private $gmail_account = NULL;
    private $gmail_passwd  = NULL;

    public function __construct()
    {
        $this->gmail_account = Config::get('gmail.account');
        $this->gmail_passwd  = Config::get('gmail.passwd');
    }

    /**
     * 新着メールを受信する
     * @return array $re データ
     */ 
    public function fetch_new_mail() : array
    {
        $re = [];

        //IMAP接続
        if (! ($mailbox = imap_open(self::MAILBOX, $this->gmail_account, $this->gmail_passwd))) {
            dx('Gmailの接続に失敗しました');
        } else {
            // $mails = imap_search($mailbox, 'UNSEEN'); 
            //未読のみ取得
            if (($mails = imap_search($mailbox, 'UNSEEN'))) {
                $i = 1;
                foreach ($mails as $msg_no) {
                    $re[] = [
                        'msg_no'  => $msg_no,
                        'subject' => $this->get_subject(imap_header($mailbox, $msg_no)), // 件名
                        'body'    => $this->get_body($mailbox, $msg_no), // 本文
                    ];
    
                    if ($i > self::GET_MAIL_LIMIT) {
                        break;
                    }
                    $i++;
                }
            }
            imap_close($mailbox);
        }
        return $re;
    }

    /**
     * ヘッダーから件名をデーコードして抽出する
     * @param object $header
     * @return string
     */
    private function get_subject(object $header) : string
    {
        if (! isset($header->subject)) {
            return '';
        }

        // 件名をデコード
        $subject = '';
        // 件名は分割されているので変換しながら結合
        foreach (imap_mime_header_decode($header->subject) as $key => $data) {
            if ($data->charset === 'default') {
                $subject .= $data->text;
            } else {
                $subject .= mb_convert_encoding($data->text, 'UTF-8', $data->charset);
            }
        }
        return $subject;
    }

    /**
     * 本文を抽出
     * ※マルチパートには対応してない
     */
    private function get_body($mailbox, $msg_no)
    {
        // とりあえず1個目を取得する
        $body = imap_fetchbody($mailbox, $msg_no, 1, FT_INTERNAL);

        // メールの構造を取得
        $struct = imap_fetchstructure($mailbox, $msg_no);

        // マルチパートのメールかどうかで、文字コードとエンコード方式を取得
        if (isset($struct->parts)) {
            //マルチパートの場合
            $charset = $struct->parts[0]->parameters[0]->value;
            $encoding = $struct->parts[0]->encoding;
        } else {
            //マルチパートではない場合
            $charset = $struct->parameters[0]->value;
            $encoding = $struct->encoding;
        }
        
        // 本文をデコードする
        $decode_body = $this->body_decode($encoding, $body);

        //文字コードをUTF-8へ
        return mb_convert_encoding($decode_body, 'UTF-8', $charset);
    }

    /**
     * 本文をエンコード方式通りにデコードする
     */
    private function body_decode(int $encoding, $body)
    {
        switch ($encoding) {
            case 1: // 8bit
                $re = imap_8bit($body);
                $re = imap_qprint($body);
                break;
            case 3: // Base64
                $re = imap_base64($body);
                break;
            case 4: // Quoted-Printable
                $re = imap_qprint($body);
                break;
            case 0: // 7bit
            case 2: // Binary
            case 5: // other
            default:
                $re = $body;
        }
        return $re;
    }

}
