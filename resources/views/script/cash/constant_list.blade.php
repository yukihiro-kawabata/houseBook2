@extends('layout.portal_header')
@section('title', '自動設定一覧')

@section('body')

<div id="pageBody" class="container-fluid">

    <div id="app"></div>

    <div class="table-responsive">
        <table class="table table-sm table-hover" style="font-size: 11px;min-width: 680px;">
            <thead>
            <tr>
                <th scope="col" style="width: 75px;">名前</th>
                <th scope="col" style="width: 50px;">金額</th>
                <th scope="col" style="width: 95px;">科目</th>
                <th scope="col">概要</th>
                <th scope="col" class="text-right">月末精算</th>
                <th scope="col" style="width: 80px;"></th>
            </tr>
            </thead>
            <tbody>
                <tr>
                    @foreach ($view['list'] as $detail_num => $detail)
                        <tr id="detail_tr_{{ $detail->id }}">
                            <td><span class="circle {{ $detail->name }}"></span>{{ $detail->name }}</td>
                            <td>{{ number_format((int)$detail->price) }}</td>
                            <td>{{ $detail->tag }}</td>
                            <td>{{ $detail->comment }}</td>
                            <td class="text-right">{{ $detail->half_flg_str }}</td>
                            <td class="text-center"><button type="button" class="button_cumstom" onclick="deleteBtn('{{ $detail->id }}', '{{ $detail->name }}', '{{ $detail->price }}', '{{ $detail->tag }}');">削除</button></td>
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

    <?php // 削除ボタン ?>
    function deleteBtn(id, name, price, tag) {
        msg = "削除対象のデータ" + '\n'
            + '対象者 : ' + name  + '\n'
            + '金額　 : ' + price + '\n'
            + '科目　 : ' + tag   + '\n'

        if (confirm("データを削除します\n\n" + msg)) {
            deleteexecute(id);
        }
    }
    <?php // 削除処理 ?>
    function deleteexecute(id) {
        $.ajax({
            url:'{{ url("/cash/constant/deleteexecute") }}',
            type:'GET',
            data:{ 'id':id }
        })
        .done(function(data) {
            location.reload();
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

</script>

@endsection
