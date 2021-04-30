@extends('layout.portal_header')
@section('title', 'リマインダー')

@section('body')

<div id="app"></div>

<div id="pageBody" class="container-fluid">

    <div class="mb-3">
      <details>
          <summary>新規追加</summary>
            <form method="GET" name="registerForm" action="{{ url('/todo/indexexecute') }}">
              <div class="row mb-3">
                <label for="title" class="col-sm-2 col-form-label">タイトル</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="title" name="title">
                </div>
              </div>
              <div class="row mb-3">
                <label for="text" class="col-sm-2 col-form-label">内容</label>
                <div class="col-sm-10">
                  <textarea class="form-control" id="text" name="text"></textarea>
                </div>
              </div>

              <div class="btn-group" role="group" aria-label="Basic example">
                <button type="button" class="btn btn-secondary m-1" 
                  onclick="document.getElementById('day_area').classList.toggle('display_off');
                          document.getElementById('day').focus();">日付指定</button>
                <button type="button" class="btn btn-secondary m-1" 
                  onclick="document.getElementById('week_area').classList.toggle('display_off');
                          document.getElementById('week').focus();
                  ">曜日指定</button>
              </div>

              <div id="day_area" class="row mb-3 display_off">
                  <label for="day" class="col-sm-2 col-form-label">日付指定</label>
                  <div class="col-sm-10">
                    <input type="date" class="form-control" id="day" name="day" value='{{ date('Y-m-d') }}'>
                  </div>
              </div>
              <div id="week_area" class="row mb-3 display_off">
                  <label for="week" class="col-sm-2 col-form-label">曜日指定</label>
                  <div class="col-sm-10">
                      <select id="week" class="form-control" name="week">
                        <option></option>
                        @foreach($view['view_week'] as $w_num => $w_val)
                          <option value="{{ $w_num }}">{{ $w_val }}</option>
                        @endforeach
                      </select>
                  </div>
              </div>

              <div class="row mb-3">
                  <label for="time" class="col-sm-2 col-form-label">時間</label>
                  <div class="col-sm-10">
                      <input type="time" class="form-control" id="time" name="time" value="18:00">
                  </div>
              </div>

              <div class="row mb-3">
                  <button type="button" class="btn btn-primary"
                    onclick="document.registerForm.submit();">登録</button>
              </div>
            </form>
      </details>
    </div>

    <div class="card shadow-lg display_off" id="my-modal">
      <img src="{{ asset('/image/pengin_tonbo.gif') }}" class="card-img-top my-modal-image" onclick="document.getElementById('my-modal').classList.add('display_off');">
      <div class="card-body">
        <h5 class="card-title">ToDoのステータス変更</h5>
        <h6 class="card-subtitle mb-2 text-muted" id="my-modal-subtitile">Non title</h6>
        <p class="card-text" id="my-modal-text">何も選択されていません</p>
        <button type="button" class="card-link btn btn-danger" onclick="todoChangeExecute(2);">削除</button>
        <button type="button" class="card-link btn btn-warning" onclick="todoChangeExecute(1);">未着手</button>
        <button type="button" class="card-link btn btn-primary"  onclick="todoChangeExecute(9);">完了</button>

        <form method="POST" name="todoResultChangeForm" action="{{ url('/todo/result/updateexecute') }}">
          <input type="hidden" name="id" id="my-modal-input-todo-id" value="">
          <input type="hidden" name="title" id="my-modal-input-todo-title" value="">
          <input type="hidden" name="day" id="my-modal-input-todo-day" value="">
          <input type="hidden" name="status" id="my-modal-input-todo-status" value="">
        </form>
      </div>
    </div>

    @foreach ($view['list'] as $n => $data)
      <div class="list-group mb-2">
        @if ((int)preg_replace('/(\d{4})-(\d{2})-(\d{2})/', '$1$2$3', $data['day']) === (int)date('Ymd'))
          <span class="badge bg-danger view_day_badge">
        @else
          <span class="badge bg-secondary view_day_badge">
        @endif
          {{ preg_replace('/(\d{4})-(\d{2})-(\d{2})/', '$2/$3', $data['day']) . '（' . $data['week_name'] . '）' }}
        </span>

        <a href="javascript:void(0)" class="list-group-item list-group-item-action {{ (array_key_exists('todo_fixed_flg', $data)) ? 'todo_fixed_status' : '' }}"
            aria-current="true" onclick="todoChangeModal('{{ $data['id'] }}', '{{ $data['title'] }}', '{{ $data['day'] }}')">
            <div class="d-flex w-100 justify-content-between">
              <p class="mb-1">{{ $data['title'] }}</p>
              <small>{{ preg_replace('/\:\d{2}$/', '', $data['time']) }}</small>
            </div>
            <small>{!! nl2br($data['text']) !!}</small>
            <small class="float-right">{{ $data['status_name'] }}</small>
        </a>
      </div>
    @endforeach

</div>

<style>
    .display_off {
        display: none;
    }
    .view_day_badge {
      width: 100px;
      margin-bottom: -10px;
      z-index: 10;
    }
    .todo_fixed_status {
      background-color: gainsboro;
      color: #999;
    }
    #my-modal {
      z-index: 11;
      position: fixed;
      top: 40%;
      width: 80%;
      margin: 0 5%;
    }
    .my-modal-image {
      width: 40px;
      position: absolute;
      top: 10px;
      right: 20px;
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

    <?php /////// ToDo変更モーダル /////// ?>
    function todoChangeModal(todo_id, title, day) {

      document.getElementById('my-modal-input-todo-id').value    = todo_id;
      document.getElementById('my-modal-input-todo-day').value   = day;
      document.getElementById('my-modal-input-todo-title').value = title;

      document.getElementById('my-modal-subtitile').innerHTML = day;
      document.getElementById('my-modal-text').innerHTML      = title;

      document.getElementById('my-modal').classList.remove('display_off');
    }

    <?php ///////////// ToDo変更処理 ///////////// ?>
    function todoChangeExecute(status) {
      document.getElementById('my-modal-input-todo-status').value = status;
      document.todoResultChangeForm.submit();
    }

</script>

@endsection
