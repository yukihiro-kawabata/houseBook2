@extends('layout.portal_header')
@section('title', 'cash一覧')

@section('body')

<div id="pageBody" class="container-fluid">

    <div style="margin: 10px;text-align: right;">
        <select class="form-control" onchange="page_reload(this);">
            @foreach (all_year_month($request['date']) as $yyyymm => $yyyymm_val)
                @if ((int)$yyyymm === (int)$request['date'])
                    <option value="{{ $yyyymm }}" selected="selected">{{ $yyyymm }}</option>
                @else
                    <option value="{{ $yyyymm }}">{{ $yyyymm }}</option>
                @endif
            @endforeach
        </select>

        <a id="list_link" href="{{url('/cash/index')}}">登録ページ</a>
    </div>
    
    <ul class="list-group list-group-flush">
        <li class="list-group-item list-group-item-success">残高<span class="badge badge-success float-right">{{ number_format($view['sum_balance']) }}</span></li>
        <li class="list-group-item list-group-item-success">Devit 使用額<span class="badge badge-success float-right">{{ number_format($view['devit_pay']) }}</span></li>
        <li class="list-group-item list-group-item-danger">今月支出<span class="badge badge-danger float-right">まだやねん</span></li>
        <li class="list-group-item list-group-item-primary">今月利益<span class="badge badge-primary float-right">まだやねん</span></li>
    </ul>

    <hr style="height: 10px;"></hr>
    
    <ul class="nav nav-tabs" style="font-size: 12px;">
        @foreach ($userDatas as $name)
            <li class="nav-item">
                @if ($name === 'ALL')
                    <a id="nav-link-{{ $name }}" class="nav-link" href="javascript:void(0)" onclick="change_tab('{{ $name }}');">{{ $name }}</a>
                @else
                    <a id="nav-link-{{ $name }}" class="nav-link" href="javascript:void(0)" onclick="change_tab('{{ $name }}');">{{ $name }}</a>
                @endif
            </li>
        @endforeach
    </ul>

    @foreach ($view['sum_kamoku_list'] as $user_name => $sum_list_data)
        <ul id="sum_kamoku_list_{{ $user_name }}" class="list-group list-group-flush display_off">
            @foreach ($sum_list_data as $num => $sum_kamoku_list)
                <li class="list-group-item">
                    {{ $sum_kamoku_list['kamoku_sum'] }}
                    @if ($sum_kamoku_list['amount_flg'] == 1) <?php // 収入 ?>
                        <span class="badge badge-primary float-right">{{ $sum_kamoku_list['amount'] }}</span>
                    @else <?php // 支出 ?>
                        <span class="badge badge-danger float-right">{{ $sum_kamoku_list['amount'] }}</span>
                    @endif
                </li>
            @endforeach
        </ul>
    @endforeach

    <hr style="height: 10px;"></hr>

    <div class="table-responsive">
        <table class="table table-sm table-hover" style="font-size: 11px;min-width: 680px;">
            <thead>
            <tr>
                <th scope="col" style="width: 75px;">名前</th>
                <th scope="col" style="width: 50px;">金額</th>
                <th scope="col" style="width: 95px;">科目</th>
                <th scope="col">概要</th>
                <th scope="col" style="width: 80px;">発生日</th>
                <th scope="col" style="width: 80px;"></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                @foreach ($view['detail'] as $detail_num => $detail)
                <tr id="detail_tr_{{ $detail->id }}">
                        <td><span class="circle {{ $detail->name }}"></span>{{ $detail->name }}</td>
                        <td>{{ number_format((int)$detail->price) }}</td>
                        <td>{{ $detail->tag }}</td>
                        <td>{{ $detail->comment }}</td>
                        <td>{{ $detail->date }}</td>
                        <td class="text-center"><button type="button" class="button_cumstom" onclick="deleteBtn('{{ $detail->id }}', '{{ $detail->name }}', '{{ $detail->price }}', '{{ $detail->tag }}', '{{ $detail->date }}');">削除</button></td>
                    </tr>
                @endforeach
            </tr>
            </tbody>
        </table>
    </div>

</div>

<style>
    .circle {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: block;
        float: left;
        margin: 3px;
    }
    .kabigon {
        background-color: #997C3D;
    }
    .yukihiro {
        background-color: #14CC7B;
    }
    .devit {
        background-color: #FF6F00;
    }
    .share {
        background-color: #007bff;
    }
    .button_cumstom {
        background-color: #c82333;
        color: #fff;
        border-radius: 15%;
        border: none;
    }
    .display_off {
        display: none;
    }
</style>

<script type="text/javascript">

    <?php // 科目ごとの集計タブ ?>
    function change_tab(name) {
        <?php // タグの中身を入れ替える & タグのアクティブを変更する ?>
        @foreach ($userDatas as $name)
            document.getElementById("sum_kamoku_list_{{ $name }}").classList.add("display_off");
            document.getElementById("nav-link-{{ $name }}").classList.remove("active");
        @endforeach
        document.getElementById("sum_kamoku_list_" + name).classList.remove("display_off");
        document.getElementById("nav-link-" + name).classList.add("active");
    }
    change_tab('ALL');<?php // デフォルトは全て ?>


    <?php // ページ更新時の処理 ?>
    function page_reload(obj) {
        pageBodyObj = document.getElementById("pageBody");
        pageBodyObj.classList.add("animated"); 
        <?php
            switch (mt_rand(1, 6)) {
                case 1:
                    $animate = "rotateOut";
                    break;
                case 2:
                    $animate = "zoomOutUp";
                    break;
                case 3:
                    $animate = "zoomOutRight";
                    break;
                case 4:
                    $animate = "zoomOutLeft";
                    break;
                case 5:
                    $animate = "hinge";
                    break;
                default:
                    $animate = "zoomOutUp";
            }
        ?>
        pageBodyObj.classList.add("{{ $animate }}");
        pageBodyObj.classList.add("slow");
        setTimeout(function() {
            location = '{{ url("/cash/list") ."?date=" }}' + obj.value;
        }, 2000);
    }

    <?php // 削除ボタン ?>
    function deleteBtn(id, name, price, tag, date) {
        msg = "<b><u>削除対象のデータ</u></b>" + '<br />'
            + '対象者 : ' + name  + '<br />'
            + '金額　 : ' + price + '<br />'
            + '科目　 : ' + tag   + '<br />'
            + '発生日 : ' + date  + '<br />'
            + '<br />'
            + '<small>10秒後に自動的にキャンセルされます</small>';
        $.confirm({
            title: '本当に削除しますか',
            content: msg,
            autoClose: 'Cancel|9000',
            buttons: {
                Yes: function () {
                    deleteexecute(id);
                },
                Cancel: function () {
                    
                }
            }
        });
    }
    <?php // 削除処理 ?>
    function deleteexecute(id) {
        $.ajax({
            url:'{{ url("/cash/deleteexecute") }}',
            type:'GET',
            data:{ 'id':id }
        })
        .done(function(data) {

            title = "1件のデータ削除をしました";
            msg = "対象者 : " + data.name + '<br />'
                + "金額　 : " + data.price + '<br />'
                + "科目　 : " + data.tag + '<br />'
                + "発生日 : " + data.date;

            toastr["success"](msg, title)
            toastr.options = {
                "closeButton": false,
                "debug": false,
                "newestOnTop": false,
                "progressBar": false,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "3000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }
            document.getElementById("detail_tr_" + data.id).remove();            
        })
        .fail(function(data) {
            toastr["error"]("Sorry try agein", "データ処理に失敗しました")

            toastr.options = {
                "closeButton": false,
                "debug": true,
                "newestOnTop": false,
                "progressBar": false,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }
        });
    }

    <?php // データ追加されたときに出す処理 ?>
    function insert_info(id)
    {
        $.ajax({
            url:'{{ url("/cash/fetch/detail") }}',
            type:'GET',
            data:{ 'id': id }
        })
        .done(function(data) {

            title = "1件のデータ登録をしました";
            msg = "対象者 : " + data.name + '<br />'
                + "金額　 : " + data.price + '<br />'
                + "科目　 : " + data.tag + '<br />'
                + "発生日 : " + data.date;

            toastr["info"](msg, title)
            toastr.options = {
                "closeButton": false,
                "debug": false,
                "newestOnTop": false,
                "progressBar": false,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "3000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }
        })
        .fail(function(data) {
            alert("データ登録に失敗した可能性があります");
        });
    }
    @if (array_key_exists('id', $request))
        insert_info('{{ $request['id'] }}');
    @endif

</script>

@endsection
