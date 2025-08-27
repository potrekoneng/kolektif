<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('single_id_cards', function (Blueprint $table) {
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

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('single_id_cards');
    }
};
