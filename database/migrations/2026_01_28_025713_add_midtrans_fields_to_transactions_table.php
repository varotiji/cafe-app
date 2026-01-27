<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up()
{
    Schema::table('transactions', function (Blueprint $table) {
        // snap_token = ID unik dari Midtrans buat munculin QR
        $table->string('snap_token')->nullable();

        // status = Buat mantau: pending (belum bayar) atau success (lunas)
        // Kita default-kan 'pending' karena kan cetak nota dulu baru bayar
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            //
        });
    }
};
