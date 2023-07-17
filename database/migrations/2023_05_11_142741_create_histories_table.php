<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('histories', function (Blueprint $table) {
            $table->id();

            $table->string('historyable_type')->nullable();
            $table->integer('historyable_id')->nullable();
            $table->json('attributes')->nullable();
            $table->string('action')->nullable();
            $table->timestamps();

            $table->integer('action_user_id')->nullable();
            $table->integer('action_admin_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('histories');
    }
}
