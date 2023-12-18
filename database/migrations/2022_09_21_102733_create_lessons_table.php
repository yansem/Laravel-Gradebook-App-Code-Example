<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLessonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->string('title',100);
            $table->text('description')->nullable();
            $table->timestamp('start')->nullable();
            $table->timestamp('end')->nullable();
            $table->unsignedSmallInteger('duration_minutes');
            $table->foreignId('teacher_id')->constrained('users');
            $table->foreignId('subject_id')->constrained();
            $table->foreignId('location_id')->constrained();
            $table->foreignId('work_id')->constrained();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lessons');
    }
}
