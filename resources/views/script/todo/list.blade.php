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

    @foreach ($view['list'] as $n => $data)

      <div class="list-group mb-2">
        <span class="badge bg-secondary view_day_badge">{{ preg_replace('/\d{4}(\d{2})(\d{2})/', '$1/$2', $data['day']) . '（' . $data['week_name'] . '）' }}</span>
        @foreach ($data['todo'] as $num => $todo)
          <a href="#" class="list-group-item list-group-item-action" aria-current="true">
              <div class="d-flex w-100 justify-content-between">
                <p class="mb-1">{{ $todo['title'] }}</p>
                <small>{{ preg_replace('/\:\d{2}$/', '', $todo['time']) }}</small>
              </div>
              <small>{{ $todo['text'] }}</small>
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
