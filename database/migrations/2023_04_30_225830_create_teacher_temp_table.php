<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeacherTempTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacher_temp', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            // $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            // $table->string('img')->nullable();
            // $table->smallInteger('status')->default(0);
            // $table->integer('university_id')->nullable();
            $table->integer('id_number')->nullable();
            // $table->integer('level')->nullable();
            $table->integer('type')->nullable();
            $table->string('description')->nullable();
            $table->boolean('accept')->default(false);
            //------------
            $table->unsignedBigInteger('section_id')->nullable();
            $table->foreign('section_id')->references('id')->on('sections');
            $table->unsignedBigInteger('colloge_id')->nullable();
            $table->foreign('colloge_id')->references('id')->on('colloges');
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
        Schema::dropIfExists('teacher_temp');
    }
}
