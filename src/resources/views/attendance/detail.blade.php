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

    <form>

        <table class="attendance-detail__table">

            <tr>
                <th>名前</th>
                <td colspan="3">西 怜奈</td>
            </tr>

            <tr>
                <th>日付</th>
                <td>2023年</td>
                <td colspan="2">6月1日</td>
            </tr>

            <tr>
                <th>出勤・退勤</th>
                <td>
                    <input type="time" value="09:00">
                </td>
                <td class="attendance-detail__wave">～</td>
                <td>
                    <input type="time" value="18:00">
                </td>
            </tr>

            <tr>
                <th>休憩</th>
                <td>
                    <input type="time" value="12:00">
                </td>
                <td class="attendance-detail__wave">～</td>
                <td>
                    <input type="time" value="13:00">
                </td>
            </tr>

            <tr>
                <th>休憩2</th>
                <td>
                    <input type="time">
                </td>
                <td class="attendance-detail__wave">～</td>
                <td>
                    <input type="time">
                </td>
            </tr>

            <tr>
                <th>備考</th>
                <td colspan="3">
                    <textarea>電車遅延のため</textarea>
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