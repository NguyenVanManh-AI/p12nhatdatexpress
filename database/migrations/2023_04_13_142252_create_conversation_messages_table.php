<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConversationMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conversation_messages', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('conversation_id')->nullable();
            $table->foreign('conversation_id')
                ->references('id')
                ->on('conversations')
                ->onDelete('CASCADE');
            $table->integer('senderable_id')->nullable();
            $table->string('senderable_type')->nullable();
            $table->text('message')->nullable();
            $table->boolean('read')->default(false);
            $table->json('options')->nullable();
            $table->string('type')->nullable()->comment('for attach');

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
        Schema::dropIfExists('conversation_messages');
    }
}
