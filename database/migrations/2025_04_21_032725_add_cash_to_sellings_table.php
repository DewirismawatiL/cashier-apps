<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('sellings', function (Blueprint $table) {
            $table->integer('cash')->after('total_price')->nullable();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('sellings', function (Blueprint $table) {
            $table->dropColumn('cash');
        });
    }
};
