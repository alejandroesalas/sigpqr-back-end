<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('description', 1000);
            $table->string('status');
            $table->unsignedBigInteger('request_type_id');
            $table->unsignedBigInteger('program_id');
            $table->unsignedBigInteger('student_id');

            $table->timestamps();
            $table->softDeletes();
            $table->foreign('request_type_id')->references('id')->on('request_types');
            $table->foreign('program_id')->references('id')->on('programs');
            $table->foreign('student_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('requests');
    }
}
