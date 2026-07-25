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

    @if(session('success'))
        <p class="success-message">
            {{ session('success') }}
        </p>
    @endif

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

        @forelse($request->attendance->breakTimes as $index => $breakTime)
        <tr>
            <th>
                {{ $index === 0 ? '休憩' : '休憩' . ($index + 1) }}
            </th>
            <td>
                <span>
                    {{ \Carbon\Carbon::parse($breakTime->break_start)->format('H:i') }}
                </span>

                <span class="request-detail__separator">～</span>

                <span>
                    {{ $breakTime->break_end
                        ? \Carbon\Carbon::parse($breakTime->break_end)->format('H:i')
                        : '' }}
                </span>
            </td>
        </tr>
        @empty
        <tr>
            <th>休憩</th>
            <td>-</td>
        </tr>
        @endforelse

        <tr>
            <th>備考</th>
            <td>
                {{ $request->note }}
            </td>
        </tr>

    </table>

    <div class="request-detail__button">

        @if($request->status === 'pending')

            <form action="{{ route('admin.request.approve', $request) }}" method="POST">
                @csrf

                <button type="submit">
                    承認
                </button>
            </form>

        @else

            <button type="button" disabled>
                承認済み
            </button>

        @endif

    </div>

</div>

@endsection