@extends('layouts.admin')

@section('title', '申請一覧')

@section('css')
<link rel="stylesheet" href="{{ asset('css/request-list.css') }}">
@endsection

@section('content')

<div class="request-list">

    <h1 class="request-list__title">
        申請一覧
    </h1>

    <div class="request-list__tabs">

        <a
            href="{{ route('admin.request.list', ['status' => 'pending']) }}"
            class="request-list__tab {{ $status === 'pending' ? 'request-list__tab--active' : '' }}"
        >
            承認待ち
        </a>

        <a
            href="{{ route('admin.request.list', ['status' => 'approved']) }}"
            class="request-list__tab {{ $status === 'approved' ? 'request-list__tab--active' : '' }}"
        >
            承認済み
        </a>

    </div>

    <div class="request-list__table-wrapper">

        <table class="request-list__table">

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
                            {{ $correctionRequest->status === 'pending' ? '承認待ち' : '承認済み' }}
                        </td>

                        <td>
                            {{ $correctionRequest->attendance->user->name }}
                        </td>

                        <td>
                            {{ \Carbon\Carbon::parse($correctionRequest->attendance->work_date)->format('Y/m/d') }}
                        </td>

                        <td>
                            {{ $correctionRequest->note }}
                        </td>

                        <td>
                            {{ $correctionRequest->created_at->format('Y/m/d') }}
                        </td>

                        <td>
                            <a href="{{ route('admin.request.detail', $correctionRequest) }}">詳細</a>
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