@extends('layout.portal_header')
@section('title', 'メール取込')

@section('body')

<div id="app"></div>

<div class="container text-center">
    <button 
        class="btn btn-primary mt-5"
        onclick="this.remove(); location.href = '{{ ('/mail/importexecute') }}' ">
        <i class="fas fa-file-import"></i>
        新着メールから自動で明細登録する
    </button>
</div>

<style>

</style>

<script type="text/javascript">

</script>

@endsection
