<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->integer('message_type');
            $table->integer('url')->nullable();
            $table->text('message')->nullable();
            $table->dateTime('sent_datetime');
            // $table->unsignedBigInteger('group_id');
            // $table->foreign('group_id')->references('id')->on('groups');
            $table->unsignedBigInteger('from');
            $table->foreign('from')->references('id')->on('users')->cascadeOnDelete();
            $table->unsignedBigInteger('to');
            $table->foreign('to')->references('id')->on('users')->cascadeOnDelete();
            // $table->dateTime('group_id');
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
        Schema::dropIfExists('messages');
    }
}