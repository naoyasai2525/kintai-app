@extends('layouts.app')

@section('title', '勤怠詳細')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance-detail.css') }}">
@endsection

@section('content')

<div class="attendance-detail">

    <h1 class="attendance-detail__title">
        勤怠詳細
    </h1>

    <form
        method="POST"
        action="{{ route('attendance.update', $attendance->id) }}"
    >
        @csrf

        <table class="attendance-detail__table">

            <tr>
                <th>名前</th>
                <td colspan="3">{{ $attendance->user->name }}</td>
            </tr>

            <tr>
                <th>日付</th>
                <td>{{ \Carbon\Carbon::parse($attendance->work_date)->format('Y年') }}</td>
                <td colspan="2">{{ \Carbon\Carbon::parse($attendance->work_date)->format('n月j日') }}</td>
            </tr>

            <tr>
                <th>出勤</th>
                <td colspan="3">
                    <input
                        type="time"
                        name="clock_in"
                        value="{{ optional($attendance->clock_in ? \Carbon\Carbon::parse($attendance->clock_in) : null)->format('H:i') }}"
                    >
                </td>
            </tr>

            <tr>
                <th>退勤</th>
                <td colspan="3">
                    <input
                        type="time"
                        name="clock_out"
                        value="{{ optional($attendance->clock_out ? \Carbon\Carbon::parse($attendance->clock_out) : null)->format('H:i') }}"
                    >
                </td>
            </tr>

            <tr>
                <th>備考</th>
                <td colspan="3">
                    <textarea name="note"></textarea>
                </td>
            </tr>

        </table>

        <div class="attendance-detail__button">
            <button type="submit">
                修正
            </button>
        </div>

    </form>

</div>

@endsection