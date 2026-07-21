@extends('layouts.admin')

@section('title', '申請詳細')

@section('css')
<link rel="stylesheet" href="{{ asset('css/request-detail.css') }}">
@endsection

@section('content')

<div class="request-detail">

    <h1 class="request-detail__title">
        申請詳細
    </h1>

    <table class="request-detail__table">

        <tr>
            <th>名前</th>
            <td>
                {{ $request->attendance->user->name }}
            </td>
        </tr>

        <tr>
            <th>日付</th>
            <td class="request-detail__date">
                <span>
                    {{ \Carbon\Carbon::parse($request->attendance->work_date)->format('Y年') }}
                </span>
                <span>
                    {{ \Carbon\Carbon::parse($request->attendance->work_date)->format('n月j日') }}
                </span>
            </td>
        </tr>

        <tr>
            <th>出勤・退勤</th>
            <td>
                <span>
                    {{ \Carbon\Carbon::parse($request->requested_clock_in)->format('H:i') }}
                </span>

                <span class="request-detail__separator">～</span>

                <span>
                    {{ \Carbon\Carbon::parse($request->requested_clock_out)->format('H:i') }}
                </span>
            </td>
        </tr>

        <tr>
            <th>休憩</th>
            <td>
                -
            </td>
        </tr>

        <tr>
            <th>休憩2</th>
            <td>
                -
            </td>
        </tr>

        <tr>
            <th>備考</th>
            <td>
                {{ $request->note }}
            </td>
        </tr>

    </table>

    <div class="request-detail__button">
        <button type="button">
            承認
        </button>
    </div>

</div>

@endsection