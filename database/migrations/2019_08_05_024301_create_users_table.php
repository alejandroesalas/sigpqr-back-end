<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('lastname');
            $table->string('email')->unique();
            $table->enum('id_type', ['CC','TI']);
            $table->string('id_num')->unique();
            $table->string('password');
            $table->string('verified');
            $table->string('status');
            $table->string('admin');
            $table->unsignedBigInteger('program_id')->nullable();
            $table->unsignedBigInteger('profile_id');
            $table->string('verification_token')->nullable();

            $table->timestamps();
            $table->softDeletes();
            $table->foreign('profile_id')->references('id')->on('profiles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
