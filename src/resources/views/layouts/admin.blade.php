<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title')</title>

    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')
</head>

<body>

    <header class="header">

        <div class="header__inner">

            <div class="header__logo">
                <img src="{{ asset('images/COACHTECH.png') }}" alt="COACHTECH">
            </div>

            <nav class="header__nav">

                <a href="/admin/attendance/list">
                    勤怠一覧
                </a>

                <a href="/admin/staff/list">
                    スタッフ一覧
                </a>

                <a href="/admin/request/list">
                    申請一覧
                </a>

                <form action="{{ route('admin.logout') }}" method="POST">
    @csrf

    <button type="submit" class="header__logout">
        ログアウト
    </button>
</form>

                </form>

            </nav>

        </div>

    </header>

    <main>
        @yield('content')
    </main>

</body>

</html>