@extends('layout.portal_header')
@section('title', '科目マスター')

@section('body')

<div id="app"></div>

<div id="pageBody" class="container-fluid">

    <button type="button" class="btn btn-outline-primary regist_form_show_btn" onclick="document.getElementById('regist_main_form').classList.toggle('display_off');">新規科目登録</button>

    <div id="regist_main_form" class="display_off">
        <form method="GET" action="{{ url('/kamoku/indexexecute') }}" name="regist_form">
            <div class="form-group">
                <label for="kamoku">末端科目</label>
                <input type="text" class="form-control" id="kamoku" name="kamoku" placeholder="CD・DVD費用">
            </div>
            <div class="form-group">
                <label for="kamoku_sum">集計科目</label>
                <select class="form-control" id="kamoku_sum" name="kamoku_sum">
                    @foreach ($view_regist['kamoku_sum_list'] as $num => $kamoku_sum_list)
                        <option value="{{ $kamoku_sum_list['kamoku_sum'] }}">{{ $kamoku_sum_list['kamoku_sum'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="amount_flg">収入・支出</label>
                <select class="form-control" id="amount_flg" name="amount_flg">
                    @foreach ($view_regist['amount_flg'] as $amount_key => $amount_val)
                        <option value="{{ $amount_key }}">{{ $amount_val }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="priority_flg">優先度(高いほうが優先)</label>
                <select class="form-control" id="priority_flg" name="priority_flg">
                    @for ($i=0; $i <=10; $i++)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="form-group">
                <button type="button" id="regist_btn" class="btn btn-primary btn-sm float-right" onclick="registBtn();">登録</button>
            </div>
        </form>
    </div>
    

    <div class="table-responsive">
        <table class="table table-sm table-hover" style="font-size: 11px;min-width: 680px;">
            <thead>
            <tr>
                <th scope="col" style="width: 95px;">集計科目</th>
                <th scope="col" style="width: 155px;">末端科目</th>
                <th scope="col" style="width: 50px;">収支</th>
                <th scope="col">優先度</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                @foreach ($view['list'] as $num => $list)
                    <tr>
                        <td>{{ $list['kamoku_sum'] }}</td>
                        <td>{{ $list['kamoku'] }}</td>
                        <td>{{ $list['amont_name'] }}</td>
                        <td>{{ $list['priority_flg'] }}</td>
                    </tr>
                @endforeach
            </tr>
            </tbody>
        </table>
    </div>

</div>

<style>
    .display_off {
        display: none;
    }
    .regist_form_show_btn {
        margin: 10px auto;
    }
    #regist_main_form {
        background-color: #fafafa;
        border: solid 1px #999;
        border-radius: 5px;
        padding: 10px;
        overflow: hidden;
        margin: 10px auto;
    }
</style>

<script type="text/javascript">
    <?php //// 登録ボタン  ?>
    function registBtn() {
        if (confirm("登録をしますか")) {
            document.getElementById("regist_btn").remove();
            document.regist_form.submit();
        }
    }
</script>

@endsection
