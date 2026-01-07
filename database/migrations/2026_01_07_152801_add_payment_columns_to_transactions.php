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
        // Kita tambahkan kolom-kolom yang kurang
        if (!Schema::hasColumn('transactions', 'payment_method')) {
            $table->string('payment_method')->nullable();
        }
        if (!Schema::hasColumn('transactions', 'snap_token')) {
            $table->text('snap_token')->nullable();
        }
        if (!Schema::hasColumn('transactions', 'items')) {
            $table->text('items')->nullable();
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
