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
                <button type="button" class="btn btn-secondary m-1" onclick="document.getElementById('day_area').classList.toggle('display_off');">日付指定</button>
                <button type="button" class="btn btn-secondary m-1" onclick="document.getElementById('week_area').classList.toggle('display_off');">曜日指定</button>
              </div>

              <div id="day_area" class="row mb-3 display_off">
                  <label for="day" class="col-sm-2 col-form-label">日付指定</label>
                  <div class="col-sm-10">
                      <input type="date" class="form-control" id="day" name="day">
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
                      <input type="time" class="form-control" id="time" name="time" value="09:00">
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
          <input type="hidden" name="json" id="my-modal-input-json" value="">
          <input type="hidden" name="todo_num" id="my-modal-input-todo_num" value="">
          <input type="hidden" name="type" id="my-modal-input-type" value="">
        </form>
      </div>
    </div>

    @foreach ($view['list'] as $n => $data)
      <div class="list-group mb-2">
        <span class="badge bg-secondary view_day_badge">{{ preg_replace('/\d{4}(\d{2})(\d{2})/', '$1/$2', $data['day']) . '（' . $data['week_name'] . '）' }}</span>
        @foreach ($data['todo'] as $num => $todo)
          <a href="javascript:void(0)" class="list-group-item list-group-item-action" aria-current="true" onclick="todoChangeModal('{{ json_encode($data) }}', '{{ $num }}')">
              <div class="d-flex w-100 justify-content-between">
                <p class="mb-1">{{ $todo['title'] }}</p>
                <small>{{ preg_replace('/\:\d{2}$/', '', $todo['time']) }}</small>
              </div>
              <small>{{ $todo['text'] }}</small>
              <small class="float-right">{{ $todo['todo_result_status_name'] }}</small>
          </a>
        @endforeach
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
    function todoChangeModal(json, todo_num) {
      const date = JSON.parse(json);
      const todo = date.todo[todo_num];
      
      let todo_text = todo.text;
      if (!todo_text) {
        todo_text = ''; <?php /// NULLという文字を画面表示したくない ?>
      }

      document.getElementById('my-modal-input-json').value = json;
      document.getElementById('my-modal-input-todo_num').value = todo_num;

      document.getElementById('my-modal-subtitile').innerHTML = String(date.day).replace(/(\d{4})(\d{2})(\d{2})/i, '$1-$2-$3');
      document.getElementById('my-modal-text').innerHTML = todo.title + '<br /> ' + todo_text;

      document.getElementById('my-modal').classList.remove('display_off');
    }

    <?php ///////////// ToDo変更処理 ///////////// ?>
    function todoChangeExecute(type_num) {
      document.getElementById('my-modal-input-type').value = type_num;
      document.todoResultChangeForm.submit();
    }

</script>

@endsection
