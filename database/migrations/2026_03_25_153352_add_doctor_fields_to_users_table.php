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
            // Add hospital relation
            $table->foreignId('hospital_id')
                ->nullable()
                ->after('email')
                ->constrained('hospitals')
                ->nullOnDelete();
            
            // Add contact info
            $table->string('phone', 20)->nullable()->after('hospital_id');
            
            // Add account status
            $table->boolean('is_active')->default(true)->after('phone');
            
            // Doctor-specific fields (NULL for non-doctors)
            $table->string('specialization')->nullable()->after('is_active');
            $table->decimal('consultation_fee', 10, 2)->nullable()->after('specialization');
            $table->json('availability')->nullable()->after('consultation_fee');
            $table->boolean('is_available')->nullable()->default(true)->after('availability');
            $table->softDeletes();

            // Indexes for faster search
            $table->index('hospital_id');
            $table->index('is_active');
            $table->index('specialization');
            $table->index(['hospital_id', 'specialization']);
            $table->index(['is_active', 'specialization']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['hospital_id']);
            $table->dropColumn([
                'hospital_id',
                'phone',
                'is_active',
                'specialization',
                'consultation_fee',
                'availability',
                'is_available'
            ]);
            $table->dropSoftDeletes();
        });
    }
};
