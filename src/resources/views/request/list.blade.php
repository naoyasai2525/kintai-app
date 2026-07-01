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

                @for ($i = 0; $i < 8; $i++)
                <tr>
                    <td>承認待ち</td>
                    <td>西 伶奈</td>
                    <td>2023/06/01</td>
                    <td>遅延のため</td>
                    <td>2023/06/02</td>
                    <td>
                        <a href="/request/detail">詳細</a>
                    </td>
                </tr>
                @endfor

            </tbody>

        </table>

    </div>

</div>

@endsection