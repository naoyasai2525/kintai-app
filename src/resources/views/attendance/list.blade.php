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

        <a href="#" class="attendance-list__prev">
            ← 前月
        </a>

        <div class="attendance-list__month">
            📅 2023/06
        </div>

        <a href="#" class="attendance-list__next">
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

                <tr>
                    <td>06/01(木)</td>
                    <td>09:00</td>
                    <td>18:00</td>
                    <td>1:00</td>
                    <td>8:00</td>
                    <td><a href="#">詳細</a></td>
                </tr>

                <tr>
                    <td>06/02(金)</td>
                    <td>09:00</td>
                    <td>18:00</td>
                    <td>1:00</td>
                    <td>8:00</td>
                    <td><a href="#">詳細</a></td>
                </tr>

                <tr>
                    <td>06/03(土)</td>
                    <td>09:00</td>
                    <td>18:00</td>
                    <td>1:00</td>
                    <td>8:00</td>
                    <td><a href="#">詳細</a></td>
                </tr>

            </tbody>

        </table>

    </div>

</div>

@endsection