<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>

    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/attendance.css') }}">
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
    @yield('css')
</head>
<body>

<header class="header">
    <div class="header__inner">

        <div class="header__logo">
            <img src="{{ asset('images/COACHTECH.png') }}" alt="COACHTECH">
        </div>

        <nav class="header__nav">

            <a href="/attendance">
                勤怠
            </a>

            <a href="/attendance/list">
                勤怠一覧
            </a>

            <a href="/request/list">
                申請
            </a>

            <form method="POST" action="/logout">
                @csrf

                <button
                    type="submit"
                    class="header__logout"
                >
                    ログアウト
                </button>
            </form>

        </nav>

    </div>
</header>

<main>
    @yield('content')
</main>

</body>
</html>