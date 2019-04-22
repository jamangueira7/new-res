<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConsentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('principal')->create('consents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code',255);
            $table->string('archive',255);
            $table->string('status',100);
            $table->string('recipient',255);
            $table->string('unimed',255);

            $table->unsignedBigInteger('user_id');

            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::connection('principal')->dropIfExists('consents');
    }
}
