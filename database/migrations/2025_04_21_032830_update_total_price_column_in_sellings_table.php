<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTotalPriceColumnInSellingsTable extends Migration
{
    public function up()
    {
        Schema::table('sellings', function (Blueprint $table) {
            $table->decimal('total_price', 20, 2)->change(); // Perbesar kapasitas kolom total_price
            $table->decimal('total_pay', 20, 2)->change();   // Perbesar kapasitas kolom total_pay
            $table->decimal('kembalian', 20, 2)->change();   // Perbesar kapasitas kolom kembalian
        });
    }

    public function down()
    {
        Schema::table('sellings', function (Blueprint $table) {
            $table->decimal('total_price', 15, 2)->change(); // Kembalikan ke ukuran sebelumnya
            $table->decimal('total_pay', 15, 2)->change();
            $table->decimal('kembalian', 15, 2)->change();
        });
    }
}
