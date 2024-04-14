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
        Schema::create('raw_device_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained();
            $table->json('data');
            $table->ipAddress('ip_address');
            $table->boolean('is_processed')->default(false);
            $table->timestamp('created_at')->useCurrent();
    
            $table->index(['device_id', 'is_processed'], 'device_processed_index');
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raw_device_data');
    }
};
