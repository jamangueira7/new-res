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
        Schema::connection('principal')->create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',100);
            $table->string('email',150)->unique();
            $table->string('cpf',15)->unique();

            $table->date('birth')->nullable();
            $table->string('password', 255);
            $table->char('sex', 1)->default('M');
            $table->string('unimed',50)->nullable()->default('0');

            $table->unsignedBigInteger('level_id');

            $table->foreign('level_id')->references('id')->on('levels');

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
        Schema::connection('principal')->dropIfExists('users');
    }
}
