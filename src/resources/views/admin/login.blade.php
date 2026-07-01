@extends('layouts.guest')

@section('title', '管理者ログイン')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin-login.css') }}">
@endsection

@section('content')

<div class="admin-login">

    <h1 class="admin-login__title">
        管理者ログイン
    </h1>

    <form class="admin-login__form">

        <div class="admin-login__group">
            <label>メールアドレス</label>
            <input type="email">
        </div>

        <div class="admin-login__group">
            <label>パスワード</label>
            <input type="password">
        </div>

        <button type="submit" class="admin-login__button">
            管理者ログインする
        </button>

    </form>

</div>

@endsection