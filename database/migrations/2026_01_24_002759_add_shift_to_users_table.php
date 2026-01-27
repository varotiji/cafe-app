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
            // Menambahkan kolom shift dan pin/password angka
            $table->string('shift')->nullable()->after('email');
            // Kita gunakan kolom password bawaan untuk PIN,
            // tapi kolom shift ini untuk filter hak akses laporan nantinya.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menghapus kolom jika migration di-rollback
            $table->dropColumn('shift');
        });
    }
};
