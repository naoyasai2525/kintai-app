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

            'note' => [
                'required',
            ],
        ];
    }

    public function messages()
    {
        return [
            'clock_in.required' => '出勤時間を入力してください',
            'clock_in.before' => '出勤時間もしくは退勤時間が不適切な値です',

            'clock_out.required' => '退勤時間を入力してください',

            'note.required' => '備考を記入してください',
        ];
    }
}