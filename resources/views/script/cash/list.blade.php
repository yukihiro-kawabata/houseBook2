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
    <li class="list-group-item list-group-item-danger">今月支出<span class="badge badge-danger float-right">{{ number_format($view['sum_balance_target_month']['expence']) }}</span></li>
        <li class="list-group-item list-group-item-primary">今月利益<span class="badge badge-primary float-right">{{ number_format($view['sum_balance_target_month']['profit']) }}</span></li>
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

    <?php //////////////////////// 明細データ //////////////////////// ?>
    <div class="table-responsive">
        <table class="table table-sm table-hover table_style">
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

    <hr style="height: 10px;"></hr>

    <?php //////////////////////// 集計科目・年月ごとのグラフ //////////////////////// ?>
    <div>
        <ul id="graph_tab" class="nav nav-tabs" style="font-size: 12px;">
            <li class="nav-item"><a id="nav-graph-food-cost" class="nav-link" href="javascript:void(0)" onclick="change_tab_graph('food-cost');">食費</a></li>
            <li class="nav-item"><a id="nav-graph-eating-out" class="nav-link" href="javascript:void(0)" onclick="change_tab_graph('eating-out');">外食</a></li>
            <li class="nav-item"><a id="nav-graph-utility-cost" class="nav-link" href="javascript:void(0)" onclick="change_tab_graph('utility-cost');">光熱費</a></li>
            <li class="nav-item"><a id="nav-graph-social-expence" class="nav-link" href="javascript:void(0)" onclick="change_tab_graph('social-expence');">遊興費</a></li>
            <li class="nav-item"><a id="nav-graph-life-cost" class="nav-link" href="javascript:void(0)" onclick="change_tab_graph('life-cost');">日用品</a></li>
        </ul>
    </div>
    <div id="app">
        <div id="food-cost-graph"><cash-list-food-cost-graph-component></cash-list-food-cost-graph-component></div>
        <div id="eating-out-graph"><cash-list-eating-out-graph-component></cash-list-eating-out-graph-component></div>
        <div id="utility-cost-graph"><cash-list-utility-cost-graph-component></cash-list-utility-cost-graph-component></div>
        <div id="social-expence-graph"><cash-list-social-expence-graph-component></cash-list-social-expence-graph-component></div>
        <div id="life-cost-graph"><cash-list-life-cost-graph-component></cash-list-life-cost-graph-component></div>
    </div>

    <?php //////////////////////// 集計科目・年月ごとのデータ //////////////////////// ?>
    <div class="table-responsive">
        <table class="table table-sm table-hover table_style">
            <thead>
                <tr>
                    <th scope="col" style="width: 75px;"></th>
                    @foreach ($view['sum_kamoku_month_list']['kamoku_list'] as $kamoku_name => $val)
                        <th scope="col" style="width: 80px;">{{ $kamoku_name }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach (all_year_month() as $month => $val)
                    <tr>
                        <th>{{ $month }}</th>
                        @foreach ($view['sum_kamoku_month_list']['kamoku_list'] as $kamoku_name => $val)
                            <td>{{ number_format($view['sum_kamoku_month_list'][$kamoku_name][$month]) }}</td>
                        @endforeach 
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>

<style>
    .table_style {
        font-size: 11px;
        min-width: 680px;
    }
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

    <?php // 科目ごとのグラフ選択タブ ?>
    function change_tab_graph(name) {
        $("#app").find("div").addClass("display_off");
        $("#graph_tab").find("a").removeClass("active");

        document.getElementById(name + "-graph").classList.remove("display_off");
        $("#" + name + "-graph").find("div").removeClass("display_off");        
        document.getElementById("nav-graph-" + name).classList.add("active");
    }


    <?php // ページ更新時の処理 ?>
    function page_reload(obj) {
        pageBodyObj = document.getElementById("pageBody");
        pageBodyObj.classList.add("animated"); 
        <?php
            switch (mt_rand(1, 6)) {
                case 1:
                    $animate = "zoomOutUp";
                    break;
                case 5:
                    $animate = "rotateOut";
                    break;
                default:
                    $animate = "hinge";
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
        msg = "削除対象のデータ" + '\n'
            + '----------------------------' + '\n'
            + '対象者 : ' + name  + '\n'
            + '金額　 : ' + price + '\n'
            + '科目　 : ' + tag   + '\n'
            + '発生日 : ' + date  + '\n'

        if (confirm("データを削除します\n\n" + msg)) {
            deleteexecute(id);
        }
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
