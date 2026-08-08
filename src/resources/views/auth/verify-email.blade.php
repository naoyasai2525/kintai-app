@extends('layouts.guest')

@section('title', 'メール認証')

@section('css')
<link rel="stylesheet" href="{{ asset('css/verify-email.css') }}">
@endsection

@section('content')

<div class="verify-email">

    <div class="verify-email__content">

        <p class="verify-email__message">
            登録していただいたメールアドレスに認証メールを送付しました。<br>
            メール認証を完了してください。
        </p>

        <a
            href="http://localhost:8025"
            target="_blank"
            class="verify-email__button"
        >
            認証はこちらから
        </a>

        @if (session('status') == 'verification-link-sent')
            <p class="verify-email__success">
                新しい認証メールを送信しました。
            </p>
        @endif

        <form
            method="POST"
            action="{{ route('verification.send') }}"
            class="verify-email__resend"
        >
            @csrf

            <button type="submit">
                認証メールを再送する
            </button>
        </form>

        <form
            method="POST"
            action="{{ route('logout') }}"
            class="verify-email__logout"
        >
            @csrf

            <button type="submit">
                ログアウト
            </button>
        </form>

    </div>

</div>

@endsection