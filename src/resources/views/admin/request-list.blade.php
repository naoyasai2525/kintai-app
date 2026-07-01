@extends('layouts.admin')

@section('title', '申請一覧')

@section('css')
<link rel="stylesheet" href="{{ asset('css/list.css') }}">
@endsection

@section('content')

<div class="request-list">

    <h1 class="request-list__title">
        申請一覧
    </h1>

    <div class="request-list__tabs">

        <a href="#" class="request-list__tab request-list__tab--active">
            承認待ち
        </a>

        <a href="#" class="request-list__tab">
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

                <tr>
                    <td>承認待ち</td>
                    <td>西 怜奈</td>
                    <td>2023/06/01</td>
                    <td>遅延のため</td>
                    <td>2023/06/02</td>
                    <td>
                        <a href="#">詳細</a>
                    </td>
                </tr>

                <tr>
                    <td>承認待ち</td>
                    <td>山田 太郎</td>
                    <td>2023/06/01</td>
                    <td>遅延のため</td>
                    <td>2023/08/02</td>
                    <td>
                        <a href="#">詳細</a>
                    </td>
                </tr>

                <tr>
                    <td>承認待ち</td>
                    <td>山田 花子</td>
                    <td>2023/06/02</td>
                    <td>遅延のため</td>
                    <td>2023/07/02</td>
                    <td>
                        <a href="#">詳細</a>
                    </td>
                </tr>

            </tbody>

        </table>

    </div>

</div>

@endsection