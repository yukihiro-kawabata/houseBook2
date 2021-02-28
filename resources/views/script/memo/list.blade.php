@extends('layout.portal_header')
@section('title', 'メモ帳')

@section('body')

<?php ///////////  シンプルマークダウン @url http://unitopi.com/markdown-editor/ /////////// ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
<script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>

<div id="app"></div>

<div class="container-fluid">

  <div class="row text-right mt-2">
    <i class="fas fa-plus-square fa-2x" id="new_memo_icon" style="color: #4a954a;"
      onclick="
        const doc = document.getElementById('addForm');
        doc.classList.toggle('display_off');
        doc.animate([{opacity: '0'}, {opacity: '1'}], 1000)
      "></i>
  </div>
  
  <?php ////////////////// 新規追加フォーム ////////////////// ?>
  <div id="addForm" class="form-group mt-3 display_off">
    <form method="POST" action="{{ url('/memo/indexexecute') }}">
      <input type="hidden" name="id" value="{{ $edit['id'] }}"> <?php /// セキュリティー的にアウトだが家庭用なのでOKにする ?>
      <input type="text" name="title" class="form-control" placeholder="タイトル" value="{{ $edit['title'] }}" />
      <textarea id="editor" name="text" rows="8" cols="40">{{ $edit['text'] }}</textarea>
      <button type="submit" class="btn btn-primary">登録</button>
    </form>
  </div>

  <?php ////////////////// 一覧 ////////////////// ?>
  <div class="mt-4">
    <ul class="list-group">
      @foreach ($view['list'] as $n => $list)
        <li class="list-group-item pointer"
            onclick="window.location = '{{ url("/memo/list?id=" . $list['id']) }}'">
          {{ $list['title'] }}
          <span class="list_time">
            {{ empty($list['updated_at']) ? $list['created_at'] : $list['updated_at'] }}
          </span>
        </li>
      @endforeach
    </ul>
  </div>

</div>

<style>
  .display_off { display: none; }
  .list_time {
    font-size: 10px;
    float: right;
    width: 60px;
    color: gray;
  }
  .pointer {
    cursor: pointer;
  }
</style>

<script type="text/javascript">
  <?php /// SimpleMDE /// ?>
  const simplemde = new SimpleMDE({
     element: document.getElementById("editor")
  });

  @if (array_key_jug('id', $edit))
    document.getElementById('new_memo_icon').click();
  @endif

</script>

@endsection
