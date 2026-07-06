@extends('layouts.app')

@section('title', '勤怠打刻')

@section('content')

<div class="attendance">

    <div class="attendance__status">
        {{ $status }}
    </div>

    <div class="attendance__date">
        {{ now()->format('Y年n月j日(D)') }}
    </div>

    <div class="attendance__time">
        {{ now()->format('H:i') }}
    </div>

    <div class="attendance__actions">

        @if ($status === '勤務外')

            <form method="POST" action="/attendance/clock-in">
                @csrf

                <button
                    type="submit"
                    class="attendance__button"
                >
                    出勤
                </button>
            </form>

        @elseif ($status === '出勤中')

    <form method="POST" action="/attendance/break-in">
        @csrf

        <button
            type="submit"
            class="attendance__button"
        >
            休憩入
        </button>
    </form>

    <form method="POST" action="/attendance/clock-out">
        @csrf

        <button
            type="submit"
            class="attendance__button"
        >
            退勤
        </button>
    </form>

@elseif ($status === '休憩中')

    <form method="POST" action="/attendance/break-out">
        @csrf

        <button
            type="submit"
            class="attendance__button"
        >
            休憩戻
        </button>
    </form>

        @elseif ($status === '退勤済')

            <p>
                お疲れ様でした。
            </p>

        @endif

    </div>

</div>

@endsection