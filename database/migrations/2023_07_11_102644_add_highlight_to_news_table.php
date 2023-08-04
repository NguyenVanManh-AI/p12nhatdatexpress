<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHighlightToNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dateTime('highlight_start')->nullable()->after('is_highlight');
            $table->dateTime('highlight_end')->nullable()->after('highlight_start');
            $table->dateTime('renew_at')->nullable()->after('highlight_end');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn('highlight_start', 'highlight_end', 'renew_at');
        });
    }
}
