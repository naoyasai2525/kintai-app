@extends('layouts.admin')

@section('title', 'スタッフ別勤怠一覧')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin-staff-attendance-list.css') }}">
@endsection

@section('content')

<div class="staff-attendance">

    <h1 class="staff-attendance__title">
        {{ $user->name }}さんの勤怠
    </h1>

    <div class="staff-attendance__month-nav">

        <a
            href="{{ route('admin.staff.attendance.list', [
                'user' => $user,
                'month' => $currentMonth->copy()->subMonth()->format('Y-m')
            ]) }}"
            class="staff-attendance__prev"
        >
            ← 前月
        </a>

        <div class="staff-attendance__month">
            📅 {{ $currentMonth->format('Y/m') }}
        </div>

        <a
            href="{{ route('admin.staff.attendance.list', [
                'user' => $user,
                'month' => $currentMonth->copy()->addMonth()->format('Y-m')
            ]) }}"
            class="staff-attendance__next"
        >
            翌月 →
        </a>

    </div>

    <div class="staff-attendance__table-wrapper">

        <table class="staff-attendance__table">

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

                @forelse($attendances as $attendance)

                    <tr>

                        <td>
                            {{ \Carbon\Carbon::parse($attendance->work_date)->format('m/d(D)') }}
                        </td>

                        <td>
                            {{ $attendance->clock_in
                                ? \Carbon\Carbon::parse($attendance->clock_in)->format('H:i')
                                : '' }}
                        </td>

                        <td>
                            {{ $attendance->clock_out
                                ? \Carbon\Carbon::parse($attendance->clock_out)->format('H:i')
                                : '' }}
                        </td>

                        <td>
                            {{ $attendance->getBreakTime() }}
                        </td>

                        <td>
                            {{ $attendance->getWorkTime() }}
                        </td>

                        <td>
                            <a
                                href="{{ route('admin.attendance.detail', $attendance) }}"
                            >
                                詳細
                            </a>
                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6">
                            勤怠データがありません
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div class="staff-attendance__button">

    <a
    href="{{ route('admin.staff.attendance.csv', [
        'user' => $user,
        'month' => $currentMonth->format('Y-m')
    ]) }}"
    class="staff-attendance__csv-button"
>
    CSV出力
</a>


    </div>

</div>

@endsection