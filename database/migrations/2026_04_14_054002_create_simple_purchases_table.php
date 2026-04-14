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
        Schema::create('simple_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->string('payment_method');
            $table->enum('payment_status', ['PAID'])->default('PAID');
            $table->enum('receipt_status', ['NOT_RECEIVED', 'PARTIAL', 'RECEIVED'])->default('NOT_RECEIVED');
            $table->decimal('total_price', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simple_purchases');
    }
};

