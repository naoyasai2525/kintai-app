@extends('layouts.admin')

@section('title', '勤怠一覧')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin-attendance-list.css') }}">
@endsection

@section('content')

<div class="attendance-list">

    <h1 class="attendance-list__title">
        {{ $currentDate->format('Y年n月j日') }}の勤怠
    </h1>

    <div class="attendance-list__date-nav">

        <a
            href="{{ route('admin.attendance.list', [
                'date' => $currentDate->copy()->subDay()->format('Y-m-d')
            ]) }}"
            class="attendance-list__prev"
        >
            ← 前日
        </a>

        <div class="attendance-list__date">
            📅 {{ $currentDate->format('Y/m/d') }}
        </div>

        <a
            href="{{ route('admin.attendance.list', [
                'date' => $currentDate->copy()->addDay()->format('Y-m-d')
            ]) }}"
            class="attendance-list__next"
        >
            翌日 →
        </a>

    </div>

    <div class="attendance-list__table-wrapper">

        <table class="attendance-list__table">

            <thead>
                <tr>
                    <th>名前</th>
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
                            {{ $attendance->user->name }}
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
                            <a href="{{ route('admin.attendance.detail', $attendance) }}">
                                詳細
                            </a>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="6">
                            この日の勤怠データはありません
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection