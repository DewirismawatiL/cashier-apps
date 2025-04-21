<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePoinMemberColumnInMembersTable extends Migration
{
    public function up()
    {
        Schema::table('members', function (Blueprint $table) {
            $table->bigInteger('poin_member')->change(); // Ubah tipe data menjadi BIGINT
        });
    }

    public function down()
    {
        Schema::table('members', function (Blueprint $table) {
            $table->integer('poin_member')->change(); // Kembalikan ke tipe sebelumnya
        });
    }
}
