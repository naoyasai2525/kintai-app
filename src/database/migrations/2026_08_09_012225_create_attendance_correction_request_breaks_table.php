<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendanceCorrectionRequestBreaksTable extends Migration
{
    public function up()
    {
        Schema::create('attendance_correction_request_breaks', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('attendance_correction_request_id');

            $table->time('break_start');
            $table->time('break_end')->nullable();

            $table->timestamps();

            $table->foreign(
                'attendance_correction_request_id',
                'acr_breaks_request_id_foreign'
            )
                ->references('id')
                ->on('attendance_correction_requests')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('attendance_correction_request_breaks');
    }
}