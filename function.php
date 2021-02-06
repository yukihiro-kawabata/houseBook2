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