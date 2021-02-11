@extends('layout.portal_header')
@section('title', 'リマインダー')

@section('body')

<div id="app"></div>

<div id="pageBody" class="container-fluid">

    <div>
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
                  <input type="text" class="form-control" id="text" name="text">
                </div>
              </div>
              <div class="row mb-3">
                  <label for="day" class="col-sm-2 col-form-label">日付指定</label>
                  <div class="col-sm-10">
                      <input type="date" class="form-control" id="day" name="day">
                  </div>
              </div>
              <div class="row mb-3">
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

    <div class="table-responsive">
        <table class="table table-sm table-hover">
            <thead>
              <tr>
                <th scope="col">タイトル</th>
                <th scope="col">日付</th>
                <th scope="col">曜日</th>
                <th scope="col">時間</th>
              </tr>
            </thead>
            <tbody>
              @foreach($view['list'] as $list_n => $list_val)
                <tr>
                  <th>
                    <details>
                      <summary>{{ $list_val->title }}</summary>
                        {{ $list_val->text }}
                    </details>
                  </th>
                  <th>{{ $list_val->day }}</th>
                  <th>{{ $list_val->week_name }}</th>
                  <th>{{ preg_replace('/\:\d{2}$/', '', $list_val->time) }}</th>
                </tr>
              @endforeach
            </tbody>
          </table>
    </div>
</div>

<style>
    .display_off {
        display: none;
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
