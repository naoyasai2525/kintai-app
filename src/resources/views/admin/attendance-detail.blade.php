@extends('layouts.admin')

@section('title', '勤怠詳細')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin-attendance-detail.css') }}">
@endsection

@section('content')

<div class="attendance-detail">

    <h1 class="attendance-detail__title">
        勤怠詳細
    </h1>

    @if(session('success'))
        <p class="success-message">
            {{ session('success') }}
        </p>
    @endif

    <form
        action="{{ route('admin.attendance.update', $attendance) }}"
        method="POST"
    >
        @csrf

        <table class="attendance-detail__table">

            <tr>
                <th>名前</th>
                <td>
                    {{ $attendance->user->name }}
                </td>
            </tr>

            <tr>
                <th>日付</th>

                <td class="attendance-detail__date">
                    <span>
                        {{ \Carbon\Carbon::parse($attendance->work_date)->format('Y年') }}
                    </span>

                    <span>
                        {{ \Carbon\Carbon::parse($attendance->work_date)->format('n月j日') }}
                    </span>
                </td>
            </tr>

            <tr>
                <th>出勤・退勤</th>

                <td>
                    <input
                        type="time"
                        name="clock_in"
                        value="{{ old(
                            'clock_in',
                            $attendance->clock_in
                                ? \Carbon\Carbon::parse($attendance->clock_in)->format('H:i')
                                : ''
                        ) }}"
                    >

                    <span>～</span>

                    <input
                        type="time"
                        name="clock_out"
                        value="{{ old(
                            'clock_out',
                            $attendance->clock_out
                                ? \Carbon\Carbon::parse($attendance->clock_out)->format('H:i')
                                : ''
                        ) }}"
                    >

                    @error('clock_in')
                        <p class="error-message">
                            {{ $message }}
                        </p>
                    @enderror

                    @error('clock_out')
                        <p class="error-message">
                            {{ $message }}
                        </p>
                    @enderror
                </td>
            </tr>

            @foreach($attendance->breakTimes as $index => $break)

                <tr>
                    <th>
                        {{ $index === 0 ? '休憩' : '休憩' . ($index + 1) }}
                    </th>

                    <td>
                        <input
                            type="hidden"
                            name="breaks[{{ $index }}][id]"
                            value="{{ $break->id }}"
                        >

                        <input
                            type="time"
                            name="breaks[{{ $index }}][break_start]"
                            value="{{ old(
                                "breaks.{$index}.break_start",
                                $break->break_start
                                    ? \Carbon\Carbon::parse($break->break_start)->format('H:i')
                                    : ''
                            ) }}"
                        >

                        <span>～</span>

                        <input
                            type="time"
                            name="breaks[{{ $index }}][break_end]"
                            value="{{ old(
                                "breaks.{$index}.break_end",
                                $break->break_end
                                    ? \Carbon\Carbon::parse($break->break_end)->format('H:i')
                                    : ''
                            ) }}"
                        >

                        @error("breaks.{$index}.break_start")
                            <p class="error-message">
                                {{ $message }}
                            </p>
                        @enderror

                        @error("breaks.{$index}.break_end")
                            <p class="error-message">
                                {{ $message }}
                            </p>
                        @enderror
                    </td>
                </tr>

            @endforeach

            @php
                $newBreakIndex = $attendance->breakTimes->count();
            @endphp

            <tr>
                <th>
                    {{ $newBreakIndex === 0
                        ? '休憩'
                        : '休憩' . ($newBreakIndex + 1) }}
                </th>

                <td>
                    <input
                        type="time"
                        name="breaks[{{ $newBreakIndex }}][break_start]"
                        value="{{ old("breaks.{$newBreakIndex}.break_start") }}"
                    >

                    <span>～</span>

                    <input
                        type="time"
                        name="breaks[{{ $newBreakIndex }}][break_end]"
                        value="{{ old("breaks.{$newBreakIndex}.break_end") }}"
                    >

                    @error("breaks.{$newBreakIndex}.break_start")
                        <p class="error-message">
                            {{ $message }}
                        </p>
                    @enderror

                    @error("breaks.{$newBreakIndex}.break_end")
                        <p class="error-message">
                            {{ $message }}
                        </p>
                    @enderror
                </td>
            </tr>

            <tr>
                <th>備考</th>

                <td>
                    <textarea
                        name="note"
                        rows="4"
                    >{{ old('note', $attendance->note) }}</textarea>

                    @error('note')
                        <p class="error-message">
                            {{ $message }}
                        </p>
                    @enderror
                </td>
            </tr>

        </table>

        <div class="attendance-detail__button-area">
            <button type="submit">
                修正
            </button>
        </div>

    </form>

</div>

@endsection