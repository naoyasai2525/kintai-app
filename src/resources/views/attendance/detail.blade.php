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
        action="{{ route('attendance.update', $attendance) }}"
    >

        @csrf

        <table class="attendance-detail__table">

            <tr>

                <th>名前</th>

                <td colspan="3">
                    {{ $attendance->user->name }}
                </td>

            </tr>

            <tr>

                <th>日付</th>

                <td>
                    {{ \Carbon\Carbon::parse($attendance->work_date)->format('Y年') }}
                </td>

                <td colspan="2">
                    {{ \Carbon\Carbon::parse($attendance->work_date)->format('n月j日') }}
                </td>

            </tr>

            <tr>

                <th>出勤・退勤</th>

                <td>

                    <input
                        type="time"
                        name="clock_in"
                        value="{{ $attendance->clock_in ? \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') : '' }}"
                    >

                </td>

                <td class="attendance-detail__wave">
                    ～
                </td>

                <td>

                    <input
                        type="time"
                        name="clock_out"
                        value="{{ $attendance->clock_out ? \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') : '' }}"
                    >

                </td>

            </tr>

            @foreach ($attendance->breakTimes as $index => $break)

                <tr>

                    <th>

                        @if ($index === 0)
                            休憩
                        @else
                            休憩{{ $index + 1 }}
                        @endif

                    </th>

                    <td>

                        <input
                            type="time"
                            name="break_start[]"
                            value="{{ $break->break_start ? \Carbon\Carbon::parse($break->break_start)->format('H:i') : '' }}"
                        >

                    </td>

                    <td class="attendance-detail__wave">
                        ～
                    </td>

                    <td>

                        <input
                            type="time"
                            name="break_end[]"
                            value="{{ $break->break_end ? \Carbon\Carbon::parse($break->break_end)->format('H:i') : '' }}"
                        >

                    </td>

                </tr>

            @endforeach

            <tr>

                <th>
                    休憩{{ $attendance->breakTimes->count() + 1 }}
                </th>

                <td>

                    <input
                        type="time"
                        name="break_start[]"
                    >

                </td>

                <td class="attendance-detail__wave">
                    ～
                </td>

                <td>

                    <input
                        type="time"
                        name="break_end[]"
                    >

                </td>

            </tr>

            <tr>

                <th>
                    備考
                </th>

                <td colspan="3">

                    <textarea
                        name="note"
                    ></textarea>

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