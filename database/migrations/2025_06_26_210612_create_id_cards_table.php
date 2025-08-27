<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('id_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agency_id')->constrained();

            $table->tinyInteger('no1')->nullable();
            $table->string('name1', 100)->nullable();
            $table->string('kelas1', 100)->nullable();
            $table->string('alamat1', 100)->nullable();
            $table->string('darah1', 100)->nullable();
            $table->string('agama1', 100)->nullable();
            $table->string('kelamin1', 100)->nullable();
            $table->string('nis1', 100)->nullable();
            $table->string('nisn1', 100)->nullable();
            $table->string('ttl1', 100)->nullable();
            $table->dateTime('write_at1')->nullable();

            $table->tinyInteger('no2')->nullable();
            $table->string('name2', 100)->nullable();
            $table->string('kelas2', 100)->nullable();
            $table->string('alamat2', 100)->nullable();
            $table->string('darah2', 100)->nullable();
            $table->string('agama2', 100)->nullable();
            $table->string('kelamin2', 100)->nullable();
            $table->string('nis2', 100)->nullable();
            $table->string('nisn2', 100)->nullable();
            $table->string('ttl2', 100)->nullable();
            $table->dateTime('write_at2')->nullable();

            $table->tinyInteger('no3')->nullable();
            $table->string('name3', 100)->nullable();
            $table->string('kelas3', 100)->nullable();
            $table->string('alamat3', 100)->nullable();
            $table->string('darah3', 100)->nullable();
            $table->string('agama3', 100)->nullable();
            $table->string('kelamin3', 100)->nullable();
            $table->string('nis3', 100)->nullable();
            $table->string('nisn3', 100)->nullable();
            $table->string('ttl3', 100)->nullable();
            $table->dateTime('write_at3')->nullable();

            $table->tinyInteger('no4')->nullable();
            $table->string('name4', 100)->nullable();
            $table->string('kelas4', 100)->nullable();
            $table->string('alamat4', 100)->nullable();
            $table->string('darah4', 100)->nullable();
            $table->string('agama4', 100)->nullable();
            $table->string('kelamin4', 100)->nullable();
            $table->string('nis4', 100)->nullable();
            $table->string('nisn4', 100)->nullable();
            $table->string('ttl4', 100)->nullable();
            $table->dateTime('write_at4')->nullable();

            $table->tinyInteger('no5')->nullable();
            $table->string('name5', 100)->nullable();
            $table->string('kelas5', 100)->nullable();
            $table->string('alamat5', 100)->nullable();
            $table->string('darah5', 100)->nullable();
            $table->string('agama5', 100)->nullable();
            $table->string('kelamin5', 100)->nullable();
            $table->string('nis5', 100)->nullable();
            $table->string('nisn5', 100)->nullable();
            $table->string('ttl5', 100)->nullable();
            $table->dateTime('write_at5')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('id_cards');
    }
};
