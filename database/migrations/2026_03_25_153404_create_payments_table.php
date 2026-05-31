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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')
                ->constrained('appointments')
                ->onDelete('cascade')
                ->unique(); // One-to-one relationship
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['cash', 'card', 'insurance']);
            $table->dateTime('payment_date');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Indexes for faster search
            $table->index('appointment_id');
            $table->index('payment_method');
            $table->index('payment_date');
            $table->index(['payment_method', 'payment_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
