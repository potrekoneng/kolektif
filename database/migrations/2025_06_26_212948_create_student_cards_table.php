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
        Schema::create('student_cards', function (Blueprint $table) {
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

            $table->tinyInteger('no6')->nullable();
            $table->string('name6', 100)->nullable();
            $table->string('kelas6', 100)->nullable();
            $table->string('alamat6', 100)->nullable();
            $table->string('darah6', 100)->nullable();
            $table->string('agama6', 100)->nullable();
            $table->string('kelamin6', 100)->nullable();
            $table->string('nis6', 100)->nullable();
            $table->string('nisn6', 100)->nullable();
            $table->string('ttl6', 100)->nullable();
            $table->dateTime('write_at6')->nullable();

            $table->tinyInteger('no7')->nullable();
            $table->string('name7', 100)->nullable();
            $table->string('kelas7', 100)->nullable();
            $table->string('alamat7', 100)->nullable();
            $table->string('darah7', 100)->nullable();
            $table->string('agama7', 100)->nullable();
            $table->string('kelamin7', 100)->nullable();
            $table->string('nis7', 100)->nullable();
            $table->string('nisn7', 100)->nullable();
            $table->string('ttl7', 100)->nullable();
            $table->dateTime('write_at7')->nullable();

            $table->tinyInteger('no8')->nullable();
            $table->string('name8', 100)->nullable();
            $table->string('kelas8', 100)->nullable();
            $table->string('alamat8', 100)->nullable();
            $table->string('darah8', 100)->nullable();
            $table->string('agama8', 100)->nullable();
            $table->string('kelamin8', 100)->nullable();
            $table->string('nis8', 100)->nullable();
            $table->string('nisn8', 100)->nullable();
            $table->string('ttl8', 100)->nullable();
            $table->dateTime('write_at8')->nullable();

            $table->tinyInteger('no9')->nullable();
            $table->string('name9', 100)->nullable();
            $table->string('kelas9', 100)->nullable();
            $table->string('alamat9', 100)->nullable();
            $table->string('darah9', 100)->nullable();
            $table->string('agama9', 100)->nullable();
            $table->string('kelamin9', 100)->nullable();
            $table->string('nis9', 100)->nullable();
            $table->string('nisn9', 100)->nullable();
            $table->string('ttl9', 100)->nullable();
            $table->dateTime('write_at9')->nullable();

            $table->tinyInteger('no10')->nullable();
            $table->string('name10', 100)->nullable();
            $table->string('kelas10', 100)->nullable();
            $table->string('alamat10', 100)->nullable();
            $table->string('darah10', 100)->nullable();
            $table->string('agama10', 100)->nullable();
            $table->string('kelamin10', 100)->nullable();
            $table->string('nis10', 100)->nullable();
            $table->string('nisn10', 100)->nullable();
            $table->string('ttl10', 100)->nullable();
            $table->dateTime('write_at10')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_cards');
    }
};
