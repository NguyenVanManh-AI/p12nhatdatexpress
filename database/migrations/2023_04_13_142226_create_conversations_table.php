<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConversationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();

            $table->integer('sender_id')->nullable();
            $table->integer('receiver_id')->nullable();
            $table->integer('admin_id')->nullable()->comment('reply support id for support chat');
            $table->string('type')->nullable();
            $table->boolean('is_support')->default(true);
            $table->string('token')->nullable();
            $table->double('rating')->default(0);
            $table->string('status')->nullable();
            $table->json('options')->nullable();
            $table->dateTime('spammed_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conversations');
    }
}
