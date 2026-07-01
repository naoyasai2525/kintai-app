@extends('layouts.guest')

@section('title', '会員登録')

@section('content')

<div class="register">

    <h1 class="register__title">
        会員登録
    </h1>

    <form method="POST" action="/register" class="register__form" novalidate>

        @csrf

        <div class="register__group">
            <label>名前</label>

            <input
                type="text"
                name="name"
                value="{{ old('name') }}"
            >

            @error('name')
                <p>{{ $message }}</p>
            @enderror
        </div>

        <div class="register__group">
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

        <div class="register__group">
            <label>パスワード</label>

            <input
                type="password"
                name="password"
            >

            @error('password')
                <p>{{ $message }}</p>
            @enderror
        </div>

        <div class="register__group">
            <label>パスワード確認</label>

            <input
                type="password"
                name="password_confirmation"
            >
        </div>

        <button
            type="submit"
            class="register__button"
        >
            登録する
        </button>

        <a
            href="/login"
            class="register__login"
        >
            ログインはこちら
        </a>

    </form>

</div>

@endsection