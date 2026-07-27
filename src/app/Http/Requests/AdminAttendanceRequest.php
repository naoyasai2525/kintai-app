<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminAttendanceRequest extends FormRequest
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
                'date_format:H:i',
            ],
            'clock_out' => [
                'required',
                'date_format:H:i',
                'after:clock_in',
            ],
            'breaks' => [
                'nullable',
                'array',
            ],
            'breaks.*.id' => [
                'nullable',
                'integer',
            ],
            'breaks.*.break_start' => [
                'nullable',
                'date_format:H:i',
            ],
            'breaks.*.break_end' => [
                'nullable',
                'date_format:H:i',
            ],
            'note' => [
                'required',
                'string',
            ],
        ];
    }

    public function messages()
    {
        return [
            'clock_in.required' => '出勤時間もしくは退勤時間が不適切な値です',
            'clock_in.date_format' => '出勤時間もしくは退勤時間が不適切な値です',

            'clock_out.required' => '出勤時間もしくは退勤時間が不適切な値です',
            'clock_out.date_format' => '出勤時間もしくは退勤時間が不適切な値です',
            'clock_out.after' => '出勤時間もしくは退勤時間が不適切な値です',

            'breaks.*.break_start.date_format' => '休憩時間が不適切な値です',
            'breaks.*.break_end.date_format' => '休憩時間が不適切な値です',

            'note.required' => '備考を記入してください',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $clockIn = $this->input('clock_in');
            $clockOut = $this->input('clock_out');
            $breaks = $this->input('breaks', []);

            foreach ($breaks as $index => $break) {

                $breakStart = $break['break_start'] ?? null;
                $breakEnd = $break['break_end'] ?? null;

                /*
                 * 開始だけ、または終了だけ入力されている場合
                 */
                if (
                    ($breakStart && !$breakEnd) ||
                    (!$breakStart && $breakEnd)
                ) {
                    $validator->errors()->add(
                        "breaks.{$index}.break_start",
                        '休憩時間が不適切な値です'
                    );

                    continue;
                }

                /*
                 * 両方空欄なら追加休憩として扱わず無視
                 */
                if (!$breakStart && !$breakEnd) {
                    continue;
                }

                /*
                 * 休憩終了が休憩開始以前
                 */
                if ($breakEnd <= $breakStart) {
                    $validator->errors()->add(
                        "breaks.{$index}.break_start",
                        '休憩時間が不適切な値です'
                    );
                }

                /*
                 * 休憩開始が出勤より前、または退勤より後
                 */
                if (
                    $clockIn &&
                    $clockOut &&
                    ($breakStart < $clockIn || $breakStart > $clockOut)
                ) {
                    $validator->errors()->add(
                        "breaks.{$index}.break_start",
                        '休憩時間が不適切な値です'
                    );
                }

                /*
                 * 休憩終了が退勤より後
                 */
                if (
                    $clockOut &&
                    $breakEnd > $clockOut
                ) {
                    $validator->errors()->add(
                        "breaks.{$index}.break_end",
                        '休憩時間もしくは退勤時間が不適切な値です'
                    );
                }
            }
        });
    }
}