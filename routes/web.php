<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


// regist of cash
Route::get('/cash/index', 'cash\cashController@indexAction');
// regist execute
Route::get('/cash/indexexecute', 'cash\cashController@indexexecute');
Route::post('/cash/indexexecute', 'cash\cashController@indexexecute');
// cash list
Route::get('/cash/list', 'cash\cashController@listAction');
// delete of cash list
Route::get('/cash/deleteexecute', 'cash\cashController@deleteexecute');
// fetch detail by id
Route::get('/cash/fetch/detail', 'cash\cashController@fetch_detail_by_id');
// constant regist cash of list
Route::get('/cash/constant/list', 'cash\cashController@constantListAction');
// delete of constant cash data
Route::get('/cash/constant/deleteexecute', 'cash\cashController@constantDeleteexecute');

// api of cash list
Route::get('/api/cash/sum_kamoku_graph', 'api\apiController@fetch_cash_sum_kamoku_graph');

// kamoku list
Route::get('/kamoku/list', 'kamoku\kamokuController@listAction');
// regist of kamoku master
Route::get('/kamoku/indexexecute', 'kamoku\kamokuController@indexexecute');

// todo list
Route::get('/todo/list', 'todo\todoController@listAction');
// register todo
Route::get('/todo/indexexecute', 'todo\todoController@indexexecute');
Route::post('/todo/indexexecute', 'todo\todoController@indexexecute');
// change todo result
Route::get('/todo/result/updateexecute', 'todo\todoController@resultUpdateexecute');
Route::post('/todo/result/updateexecute', 'todo\todoController@resultUpdateexecute');

// mail list
Route::get('/mail/import', 'mail\mailController@importAction');
// register cash list from mail
Route::get('/mail/importexecute', 'mail\mailController@importexecute');

// memo register
Route::get('/memo/indexexecute', 'memo\memoController@indexexecute');
Route::post('/memo/indexexecute', 'memo\memoController@indexexecute');
// memo list
Route::get('/memo/list', 'memo\memoController@listAction');

