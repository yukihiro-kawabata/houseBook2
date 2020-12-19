@extends('layout.portal_header')
@section('title', 'カビゴンポータル')

@section('body')

<div class="container-fluid">
    <ul>
        <li><a href="{{ url('/cash/list') }}">家計簿明細</a></li>
    </ul>
</div>