<?php

function dx($param = '')
{
    echo ('<pre>');
    var_dump($param);
    echo ('</pre>');
    exit();
}

function d($param = '')
{
    echo ('<pre>');
    var_dump($param);
    echo ('</pre>');
}

// 今月から18ヶ月前までの年月を返す
function all_year_month() : array
{
    $re = [];
    $cnt = 0;
    for ($i = date('Ym'); $i > 0; $i--) {        
        if ($cnt === 13) break; // 18ヶ月前
        if (preg_replace('/^\d{4}/', '', $i) === '00') {
            $i = (int)preg_replace('/\d{2}$/', '', $i) - 1 . '12';
        }
        $re[$i] = '';
        $cnt++;
    }
    return $re;
}

/*
 * check if the value of the target key is in the array.
 * Optionanlly stop on error message
 * 
 * @param $key int|string key to check
 * @param $array array array to check
 * @param $msg string error message
 * @return boolean true: exists, false: not exists
 */ 
function array_key_jug($key, array $array, string $msg = '')
{
    if (!array_key_exists($key, $array)) $array[$key] = '';
    if (!empty($array[$key])) {
        return true;
    } else {
        if (!empty($msg)) exit($msg);

        return false;
    }
}
/** 改行を取り除く */
function remove_new_line_str(string $str)
{
    return preg_replace('/\r\n|\r|\n/', '', $str);
}
/** 空白を取り除く */
function remove_space_str(string $str)
{
    return preg_replace('/ |　/', '', $str);
}
/** 金額に入っているカンマを取り除く */
function replace_comma_for_type_integer(string $str) : int
{
    return (int)preg_replace('/,|、|，|/', '', $str);
}

/** phpのdate関数のweekに対応する曜日 */
function get_php_date_week_ja() : array
{
    $todo_model = new \App\Model\todo\todo_model();
    return $todo_model::DAY_OF_WEEKS;
}

/**
 * 今日からn年後までの日付全てを取得する
 * @param int $next_year n年後
 * @return array [yyyymmdd => ['day' => yyyymmdd, 'week_name' => '月曜'] ]
 */
function get_all_days(int $next_year = 1) : array
{
    $re = [];
    for ($i = date('Ymd', strtotime("-1 month")); $i <= date('Ymd', strtotime("$next_year year")); $i++) {
        $last_month = date('Ymd', strtotime('last day of ' . $i));

        // もし、$last_month で上手く値が取得できない場合は再取得する
        if ($last_month === '19700101') {
            $next_day = $i + 1;
            $last_month = date('Ymd', strtotime('last day of ' . $next_day));
        }

        if ($i > $last_month) {             
            // 翌月を取得する。5の数字はテキトー
            // $i = 2/29 の数字が入ると、php内部で3/1と認識されて翌月として4月が取得されてしまうため
            $next_month = date('Ymd', strtotime('1 month', strtotime($i - 5)));

            // 翌月の初日を設定する
            $i = date('Ymd', strtotime('first day of ' . $next_month));

            $i = $i - 1; // ループの部分で +1 されるので調整
            continue;
        }

        $re[$i] = [
            'day'       => (int)$i, 
            'week'      => date('w', strtotime($i)),
            'week_name' => get_php_date_week_ja()[date('w', strtotime($i))],
        ];
    }
    return $re;
}

/**
 * laravelの標準装備でデータ取得すると
 * オブジェクト型と配列型が混ざるので全て配列型に入れ直す
 */
function put_laravel_db_array($param) : array
{
    $re = [];
    foreach ($param as $num => $data) {
        $re[] = (array)$data;
    }
    return $re;
}