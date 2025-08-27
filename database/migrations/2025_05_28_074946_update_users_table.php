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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->after('id')->constrained();
            $table->string('username', 100)->after('id')->nullable();
            $table->enum('locked', ['locked', 'unlocked'])->default('unlocked')->after('name');
            $table->string('photo', 100)->nullable()->after('name');
            $table->string('tmp_lahir', 100)->nullable()->after('name');
            $table->date('tgl_lahir', 100)->nullable()->after('name');
            $table->string('nisn', 100)->nullable()->after('name');
            $table->string('nis', 100)->nullable()->after('name');
            $table->string('kelamin', 100)->nullable()->after('name');
            $table->string('agama', 100)->nullable()->after('name');
            $table->string('darah', 100)->nullable()->after('name');
            $table->string('alamat', 100)->nullable()->after('name');
            $table->string('kelas', 100)->nullable()->after('name');
            $table->foreignId('agency_id')->after('kelas')->nullable()->constrained();
            $table->softDeletes('deleted_at', precision: 0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
            $table->dropForeign(['agency_id']);
            $table->dropColumn('agency_id');
            $table->dropColumn('username');
            $table->dropColumn('tmp_lahir');
            $table->dropColumn('tgl_lahir');
            $table->dropColumn('nisn');
            $table->dropColumn('nis');
            $table->dropColumn('kelamin');
            $table->dropColumn('agama');
            $table->dropColumn('darah');
            $table->dropColumn('alamat');
            $table->dropColumn('kelas');
            $table->dropColumn('locked');
            $table->dropColumn('deleted_at');
            // $table->dropForeign(['role_id']);
        });
    }
};
