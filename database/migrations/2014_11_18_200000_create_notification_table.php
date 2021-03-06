<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification', function (Blueprint $table) {
            $table->increments('id');
            $table->char('uuid', 36)->unique();
            $table->char('txid', 64)->index();
            $table->integer('confirmations');
            $table->integer('monitored_address_id')->unsigned()->nullable();
            $table->foreign('monitored_address_id')->references('id')->on('monitored_address');
            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->tinyInteger('status')->index();
            $table->integer('attempts')->nullable();
            $table->timestamp('returned')->nullable();
            $table->text('error')->nullable();
            $table->longText('notification');
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
        Schema::drop('notification');
    }
}
