<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOneTimeConfirmTokenToUserDepositTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_deposit', function (Blueprint $table) {
            $table->string('one_time_confirm_token')->nullable()->after('deposit_status');
            $table->json('options')->nullable()->after('deposit_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_deposit', function (Blueprint $table) {
            $table->dropColumn('one_time_confirm_token', 'options');
        });
    }
}
