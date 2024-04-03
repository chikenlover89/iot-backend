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
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('name', 30);
            $table->string('description', 255);
            $table->string('network', 30);
            $table->string('type', 30);
            $table->unsignedSmallInteger('signal')->nullable();
            $table->timestamp('last_activity')->nullable();
            $table->foreignId('account_id')->constrained()->index();
            $table->foreignId('location_id')->default(0);
            $table->string('handler_key', 64)->unique()->index();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
