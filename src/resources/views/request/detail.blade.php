@extends('layouts.app')

@section('title', '申請詳細')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance-detail.css') }}">
@endsection

@section('content')

<div class="attendance-detail">

    <h1 class="attendance-detail__title">
        申請詳細
    </h1>

    <table class="attendance-detail__table">

        <tr>
            <th>名前</th>
            <td colspan="3">
                {{ $request->attendance->user->name }}
            </td>
        </tr>

        <tr>
            <th>日付</th>
            <td>
                {{ \Carbon\Carbon::parse($request->attendance->work_date)->format('Y年') }}
            </td>
            <td colspan="2">
                {{ \Carbon\Carbon::parse($request->attendance->work_date)->format('n月j日') }}
            </td>
        </tr>

        <tr>
            <th>出勤</th>
            <td colspan="3">
                <input
                    type="time"
                    value="{{ \Carbon\Carbon::parse($request->requested_clock_in)->format('H:i') }}"
                    disabled
                >
            </td>
        </tr>

        <tr>
            <th>退勤</th>
            <td colspan="3">
                <input
                    type="time"
                    value="{{ \Carbon\Carbon::parse($request->requested_clock_out)->format('H:i') }}"
                    disabled
                >
            </td>
        </tr>

        <tr>
            <th>備考</th>
            <td colspan="3">
                <textarea disabled>{{ $request->note }}</textarea>
            </td>
        </tr>

        <tr>
            <th>状態</th>
            <td colspan="3">
                {{ $request->status === 'pending' ? '承認待ち' : '承認済み' }}
            </td>
        </tr>

    </table>

</div>

@endsection