@extends('layout.portal_header')
@section('title', 'cash登録')

@section('body')

<div id="loading" class="display_off" style="opacity:0;"><i class="fas fa-spinner fa-5x fa-spin"></i></div>

<div id="main_form" class="container-fluid">
    <form method="POST" name="submitForm" action="{{ url('/cash/indexexecute') }}">
        <div style="margin: 10px;text-align: right;overflow: hidden;">
            <a id="list_link" class="float-right" href="{{url('/cash/list')}}">一覧ページ</a>
            <a id="list_link" class="float-left" href="{{url('/cash/index') . '?constant=true'}}">自動登録設定</a>
		</div>

        @if ($request['constant']) <?php // 自動登録設定のとき ?>
            <input type="hidden" name="constant" value="1">
        @else
            <div class="form-group">
                <label>日時</label>
                <input type="text" id="date" class="form-control input-sm" name="date" placeholder="日時" value="{{date('Y-m-d')}}" size="20">
            </div>
        @endif

        <div class="form-group">
            <label>対象者</label>
            <select id="name" name="name">
                @foreach ($userDatas as $userName)
                    <option value="{{ $userName }}">{{ $userName }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>金額</label>
            <input type="number" step="100" class="form-control input-sm" id="price" name="price" placeholder="金額" value="" size="20">
        </div>

        <div class="form-group">
            <label>勘定科目</label>
            <select id="kamoku_id" name="kamoku_id" class="form-control">
                <option></option>
                @foreach ($kamokuDatas as $kamokuDataNum => $kamokuData)
                    <option value="{{ $kamokuData['kamoku_id'] }}">{{ $kamokuData['kamoku'] }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>概要</label>
            <input type="text" class="form-control input-sm" id="comment" name="comment" placeholder="概要">
        </div>

        <div class="form-group" style="display:none">
            <label>詳細</label>
            <textarea class="form-control  input-sm" rows="3" id="detail" name="detail" placeholder="詳細"></textarea>
        </div>

        <div class="form-group">
            <button type="button" id="submit_btn" onclick="submitBtn();" class="btn btn-success">データ登録<span class="glyphicon glyphicon-chevron-right"></span></button>
        </div>

    </form>    
</div>

<style>
    <?php // ローディング ?>
    #loading {
        position: fixed;
        top: 40%;
        left: 32%;
    }
    .display_off {
        display: none;
    }
    #header_nav {
        display: none;
    }
    @if ($request['constant']) <?php // 自動登録設定のとき ?>
        body {
            background-color: #d9f1ff !important
        }
    @endif
</style>

<script type="text/javascript">
    $('#kamoku_id').select2();

    // $('#date').datepicker({
    //     dateFormat: 'yy-mm-dd',
    // });

    function submitBtn() {
        document.getElementById("submit_btn").classList.add("display_off");
        $("#main_form").animate({opacity:0}, 800);
        document.getElementById("loading").classList.remove("display_off");
        $("#loading").animate({opacity:1}, 1500);
        document.submitForm.submit();
    }

</script>

@endsection
