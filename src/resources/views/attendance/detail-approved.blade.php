@extends('layouts.app')

@section('title', '勤怠詳細')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance-detail-approved.css') }}">
@endsection

@section('content')

<div class="detail">

    <h1 class="detail__title">
        勤怠詳細
    </h1>

    <div class="detail__table">

        <div class="detail__row">
            <div class="detail__label">名前</div>
            <div class="detail__value">西　怜奈</div>
        </div>

        <div class="detail__row">
            <div class="detail__label">日付</div>

            <div class="detail__date">
                <span>2023年</span>
                <span>6月1日</span>
            </div>
        </div>

        <div class="detail__row">
            <div class="detail__label">出勤・退勤</div>

            <div class="detail__time">
                <span>09:00</span>
                <span>〜</span>
                <span>18:00</span>
            </div>
        </div>

        <div class="detail__row">
            <div class="detail__label">休憩</div>

            <div class="detail__time">
                <span>12:00</span>
                <span>〜</span>
                <span>13:00</span>
            </div>
        </div>

        <div class="detail__row">
            <div class="detail__label">備考</div>
            <div class="detail__value">
                電車遅延のため
            </div>
        </div>

    </div>

    <p class="detail__message">
        ※承認待ちのため修正はできません。
    </p>

</div>

@endsection