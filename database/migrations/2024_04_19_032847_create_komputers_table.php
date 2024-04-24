<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKomputersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('komputer', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('kode')->unique();
            $table->string('nama')->nullable();
            $table->date('tanggal_pembelian')->nullable();
            $table->foreignId('ip_address_id')->nullable()->constrained('ip_address');
            $table->text('spesifikasi')->nullable();
            $table->text('keterangan')->nullable();
            $table->foreignId('department_id')->nullable()->constrained('department');
            $table->string('nama_user')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('komputer');
    }
}
