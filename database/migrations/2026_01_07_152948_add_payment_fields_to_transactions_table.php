<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Kita cek dulu, kalau kolom belum ada, baru kita tambah
            if (!Schema::hasColumn('transactions', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('total_price');
            }
            if (!Schema::hasColumn('transactions', 'snap_token')) {
                $table->string('snap_token')->nullable()->after('payment_method');
            }
            if (!Schema::hasColumn('transactions', 'items')) {
                $table->text('items')->nullable()->after('snap_token');
            }
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'snap_token', 'items']);
        });
    }
};
