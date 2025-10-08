<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('perangkat_jaringan', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->string('tipe', 50);
            $table->string('lokasi', 150);
            $table->enum('status', ['aktif', 'tidak_aktif'])->default('aktif');
            $table->timestamps();
            $table->index('nama');
            $table->index('lokasi');
        });
    }

    public function down()
    {
        Schema::dropIfExists('perangkat_jaringan');
    }
};
