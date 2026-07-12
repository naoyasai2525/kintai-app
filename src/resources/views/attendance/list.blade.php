@extends('layouts.app')

@section('title', '勤怠一覧')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance-list.css') }}">
@endsection

@section('content')

<div class="attendance-list">

    <h1 class="attendance-list__title">
        勤怠一覧
    </h1>

    <div class="attendance-list__month-nav">

        <a
            href="{{ url('/attendance/list?month=' . $currentMonth->copy()->subMonth()->format('Y-m')) }}"
            class="attendance-list__prev"
        >
            ← 前月
        </a>

        <div class="attendance-list__month">
            📅 {{ $currentMonth->format('Y/m') }}
        </div>

        <a
            href="{{ url('/attendance/list?month=' . $currentMonth->copy()->addMonth()->format('Y-m')) }}"
            class="attendance-list__next"
        >
            翌月 →
        </a>

    </div>

    <div class="attendance-list__table-wrapper">

        <table class="attendance-list__table">

            <thead>

                <tr>
                    <th>日付</th>
                    <th>出勤</th>
                    <th>退勤</th>
                    <th>休憩</th>
                    <th>合計</th>
                    <th>詳細</th>
                </tr>

            </thead>

            <tbody>

                @foreach ($attendances as $attendance)

                    <tr>

                        <td>
                            {{ \Carbon\Carbon::parse($attendance->work_date)->format('m/d(D)') }}
                        </td>

                        <td>
                            {{ $attendance->clock_in ? \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') : '' }}
                        </td>

                        <td>
                            {{ $attendance->clock_out ? \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') : '' }}
                        </td>

                        <td>
                            {{ $attendance->getBreakTime() }}
                        </td>

                        <td>
                            {{ $attendance->getWorkTime() }}
                        </td>

                        <td>

                            <a href="{{ route('attendance.detail', $attendance) }}">
                                詳細
                            </a>

                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>

@endsection