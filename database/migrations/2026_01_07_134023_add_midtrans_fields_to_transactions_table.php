<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan Perubahan.
     */
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // snap_token = ID unik dari Midtrans untuk memunculkan popup bayar
            $table->string('snap_token')->nullable()->after('total_price');

            // status = Biar kita tau pembayarannya (pending, settlement, dll)
            $table->string('status')->default('pending')->after('snap_token');
        });
    }

    /**
     * Batalkan Perubahan.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['snap_token', 'status']);
        });
    }
};
