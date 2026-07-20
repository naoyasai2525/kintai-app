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

        <a href="#" class="request__tab-item request__tab-item--active">
            承認待ち
        </a>

        <a href="#" class="request__tab-item">
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

                @forelse($requests as $request)

                <tr>
                    <td>
                        {{ $request->status == 'pending' ? '承認待ち' : '承認済み' }}
                    </td>

                    <td>
                        {{ $request->attendance->user->name }}
                    </td>

                    <td>
                        {{ \Carbon\Carbon::parse($request->attendance->work_date)->format('Y/m/d') }}
                    </td>

                    <td>
                        {{ $request->note }}
                    </td>

                    <td>
                        {{ $request->created_at->format('Y/m/d') }}
                    </td>

                    <td>
                        <a href="/request/detail">
                            詳細
                        </a>
                    </td>
                </tr>

                @empty

                <tr>
                    <td colspan="6" style="text-align:center;">
                        申請はありません
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection