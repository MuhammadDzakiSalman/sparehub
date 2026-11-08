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
        Schema::create('tools', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('rack_location')->nullable();
            $table->unsignedInteger('total_quantity')->default(1);
            $table->unsignedInteger('maintenance_quantity')->default(0);
            $table->enum('status', ['available', 'borrowed', 'unavailable'])->default('available');
            $table->timestamps();
            
            $table->index('name');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tools');
    }
};
