@extends('layouts.guest')

@section('title', 'ログイン')

@section('css')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')

<div class="login">

    <h1 class="login__title">
        ログイン
    </h1>

    <form method="POST" action="/login" class="login__form" novalidate>

        @csrf

        <div class="login__group">
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

        <div class="login__group">
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
            class="login__button"
        >
            ログインする
        </button>

        <a
            href="/register"
            class="login__register"
        >
            会員登録はこちら
        </a>

    </form>

</div>

@endsection