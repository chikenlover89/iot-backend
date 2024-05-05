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
        Schema::create('alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained();
            $table->foreignId('device_id')->default(0);
            $table->string('name');
            $table->string('description');
            $table->string('type');
            $table->boolean('resolved')->default(false);
            $table->softDeletes();
            $table->timestamps();

            $table->index(['device_id', 'resolved'], 'device_resolved_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alerts');
    }
};
