@extends('layouts.admin')

@section('title', '申請詳細')

@section('css')
<link rel="stylesheet" href="{{ asset('css/request-detail.css') }}">
@endsection

@section('content')

<div class="request-detail">

    <h1 class="request-detail__title">
        勤怠詳細
    </h1>

    <table class="request-detail__table">

        <tr>
            <th>名前</th>
            <td>西 怜奈</td>
        </tr>

        <tr>
            <th>日付</th>
            <td class="request-detail__date">
                <span>2023年</span>
                <span>6月1日</span>
            </td>
        </tr>

        <tr>
            <th>出勤・退勤</th>
            <td>
                <span>09:00</span>
                <span class="request-detail__separator">～</span>
                <span>18:00</span>
            </td>
        </tr>

        <tr>
            <th>休憩</th>
            <td>
                <span>12:00</span>
                <span class="request-detail__separator">～</span>
                <span>13:00</span>
            </td>
        </tr>

        <tr>
            <th>休憩2</th>
            <td>
            </td>
        </tr>

        <tr>
            <th>備考</th>
            <td>
                電車遅延のため
            </td>
        </tr>

    </table>

    <div class="request-detail__button">
        <button>
            承認
        </button>
    </div>

</div>

@endsection