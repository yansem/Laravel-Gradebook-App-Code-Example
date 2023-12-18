<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGradeUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grade_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('group_id')->constrained();
            $table->foreignId('work_id')->constrained();
            $table->foreignId('grade_id')->constrained();
            $table->timestamp('date')->nullable();
            $table->string('comment', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('grade_users');
    }
}
