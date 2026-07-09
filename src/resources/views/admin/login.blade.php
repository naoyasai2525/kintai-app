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

    <form
        method="POST"
        action="/admin/login"
        class="admin-login__form"
        novalidate
    >

        @csrf

        <div class="admin-login__group">

            <label>メールアドレス</label>

            <input
                type="email"
                name="email"
                value="{{ old('email') }}"
            >

            @error('email')
                <p>{{ $message }}</p>
            @enderror

        </div>

        <div class="admin-login__group">

            <label>パスワード</label>

            <input
                type="password"
                name="password"
            >

            @error('password')
                <p>{{ $message }}</p>
            @enderror

        </div>

        <button
            type="submit"
            class="admin-login__button"
        >
            管理者ログインする
        </button>

    </form>

</div>

@endsection