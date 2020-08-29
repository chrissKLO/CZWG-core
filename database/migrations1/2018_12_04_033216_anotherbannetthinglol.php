<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Anotherbannetthinglol extends Migration
{
    /**
     * Run the migrations1.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('core_info', function ($table) {
            $table->text('bannerMode');
            $table->text('bannerLink');
        });
    }

    /**
     * Reverse the migrations1.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('core_info', function ($table) {
            $table->dropColumn('bannerMode');
            $table->dropColumn('bannerLink');
        });
    }
}
