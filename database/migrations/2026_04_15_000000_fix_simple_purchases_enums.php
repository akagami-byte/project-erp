<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Raw SQL untuk MySQL ENUM ALTER (no doctrine/dbal needed)
        DB::statement("ALTER TABLE simple_purchases MODIFY COLUMN payment_status ENUM('UNPAID', 'PAID') DEFAULT 'PAID'");
        DB::statement("ALTER TABLE simple_purchases MODIFY COLUMN receipt_status ENUM('NOT_RECEIVED', 'PARTIAL', 'RECEIVED') DEFAULT 'NOT_RECEIVED'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('simple_purchases', function (Blueprint $table) {
            $table->enum('payment_status', ['PAID'])->default('PAID')->change();
        });
    }
};

