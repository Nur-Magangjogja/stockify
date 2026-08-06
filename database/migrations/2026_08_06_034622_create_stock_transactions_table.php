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
        Schema::create('stock_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('Product_id')
            ->constrained()
            ->cascadeOnUpdate()
            ->restrictOnDelete();
            $table->foreignId('User_id')
            ->constrained()
            ->cascadeOnUpdate()
            ->restrictOnDelete();
            $table->enum('type', [
                'Masuk',
                'Keluar'
            ]);
            $table->unsignedInteger('quantity');
            $table->date('date');
            $table->enum('status', [
                'Pending',
                'Diterima',
                'Ditolak',
                'Dikeluarkan'
            ]);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Stock_transactions');
    }
};
