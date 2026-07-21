@extends('layouts.app')

@section('title', '申請一覧')

@section('css')
<link rel="stylesheet" href="{{ asset('css/user-request-list.css') }}">
@endsection

@section('content')

<div class="request">

    <h1 class="request__title">
        申請一覧
    </h1>

    <div class="request__tab">

        <a
            href="{{ route('request.list', ['status' => 'pending']) }}"
            class="request__tab-item
                {{ $status === 'pending' ? 'request__tab-item--active' : '' }}"
        >
            承認待ち
        </a>

        <a
            href="{{ route('request.list', ['status' => 'approved']) }}"
            class="request__tab-item
                {{ $status === 'approved' ? 'request__tab-item--active' : '' }}"
        >
            承認済み
        </a>

    </div>

    <div class="request__table-wrap">

        <table class="request__table">

            <thead>
                <tr>
                    <th>状態</th>
                    <th>名前</th>
                    <th>対象日時</th>
                    <th>申請理由</th>
                    <th>申請日時</th>
                    <th>詳細</th>
                </tr>
            </thead>

            <tbody>

                @forelse ($requests as $correctionRequest)

                    <tr>
                        <td>
                            {{ $correctionRequest->status === 'pending'
                                ? '承認待ち'
                                : '承認済み' }}
                        </td>

                        <td>
                            {{ $correctionRequest->attendance->user->name }}
                        </td>

                        <td>
                            {{ \Carbon\Carbon::parse(
                                $correctionRequest->attendance->work_date
                            )->format('Y/m/d') }}
                        </td>

                        <td>
                            {{ $correctionRequest->note }}
                        </td>

                        <td>
                            {{ $correctionRequest->created_at->format('Y/m/d') }}
                        </td>

                        <td>
                            <a href="{{ route('request.detail', $correctionRequest) }}">
                                詳細
                            </a>
                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="6" style="text-align: center;">
                            {{ $status === 'pending'
                                ? '承認待ちの申請はありません'
                                : '承認済みの申請はありません' }}
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection
