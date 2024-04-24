<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSistemOperasiFromKomputerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('komputer', function (Blueprint $table) {
            $table->foreignId('sistem_operasi_id')->nullable()->constrained('sistem_operasi');
            $table->foreignId('sistem_operasi_detail_id')->nullable()->constrained('sistem_operasi_detail');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('komputer', function (Blueprint $table) {
            $table->dropConstrainedForeignId('sistem_operasi_id');
            $table->dropConstrainedForeignId('sistem_operasi_detail_id');
        });
    }
}
