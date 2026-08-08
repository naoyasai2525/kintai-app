<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceCorrectionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'clock_in' => [
                'required',
                'before:clock_out',
            ],

            'clock_out' => [
                'required',
            ],

            'breaks' => [
                'nullable',
                'array',
            ],

            'breaks.*.break_start' => [
                'nullable',
                'after_or_equal:clock_in',
                'before_or_equal:clock_out',
            ],

            'breaks.*.break_end' => [
                'nullable',
                'after_or_equal:breaks.*.break_start',
                'before_or_equal:clock_out',
            ],

            'note' => [
                'required',
            ],
        ];
    }

    public function messages()
    {
        return [
            'clock_in.required' =>
                '出勤時間を入力してください',

            'clock_in.before' =>
                '出勤時間もしくは退勤時間が不適切な値です',

            'clock_out.required' =>
                '退勤時間を入力してください',

            'breaks.*.break_start.after_or_equal' =>
                '休憩時間が不適切な値です',

            'breaks.*.break_start.before_or_equal' =>
                '休憩時間が不適切な値です',

            'breaks.*.break_end.after_or_equal' =>
                '休憩時間が不適切な値です',

            'breaks.*.break_end.before_or_equal' =>
                '休憩時間もしくは退勤時間が不適切な値です',

            'note.required' =>
                '備考を記入してください',
        ];
    }
}