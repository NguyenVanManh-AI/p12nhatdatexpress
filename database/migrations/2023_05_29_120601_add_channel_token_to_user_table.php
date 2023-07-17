<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddChannelTokenToUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user', function (Blueprint $table) {
            $table->string('channel_token')->nullable()->after('is_active');
            $table->dateTime('spammed_at')->nullable()->after('channel_token');
        });

        Schema::table('admin', function (Blueprint $table) {
            $table->string('channel_token')->nullable()->after('admin_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user', function (Blueprint $table) {
            $table->dropColumn(['channel_token', 'spammed_at']);
        });

        Schema::table('admin', function (Blueprint $table) {
            $table->dropColumn('channel_token');
        });
    }
}
