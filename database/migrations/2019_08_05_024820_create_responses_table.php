<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('responses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('description', 1000);
            $table->integer('status_response')->unsigned();
            $table->integer('type')->unsigned();
            $table->unsignedBigInteger('request_id');
            $table->integer('user_id')->unsigned();
            $table->string('user_email');
            $table->string('type_user');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('request_id')->references('id')->on('requests');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('responses');
    }
}
