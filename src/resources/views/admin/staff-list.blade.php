@extends('layouts.admin')

@section('title', 'スタッフ一覧')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin-staff-list.css') }}">
@endsection

@section('content')

<div class="staff-list">

    <h1 class="staff-list__title">
        スタッフ一覧
    </h1>

    <div class="staff-list__table-wrapper">

        <table class="staff-list__table">

            <thead>
                <tr>
                    <th>名前</th>
                    <th>メールアドレス</th>
                    <th>月次勤怠</th>
                </tr>
            </thead>

            <tbody>

                @forelse($users as $user)

                    <tr>

                        <td>
                            {{ $user->name }}
                        </td>

                        <td>
                            {{ $user->email }}
                        </td>

                        <td>
                            <a
                                href="{{ route('admin.staff.attendance.list', $user) }}"
                            >
                                詳細
                            </a>
                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="3">
                            スタッフが登録されていません
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection