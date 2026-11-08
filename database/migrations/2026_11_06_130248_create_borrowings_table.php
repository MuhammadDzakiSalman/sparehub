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
        Schema::create('borrowings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tool_id')->constrained('tools')->restrictOnDelete();
            $table->foreignUuid('technician_id')->constrained('technicians')->restrictOnDelete();
            $table->foreignUuid('recorded_by')->constrained('users')->restrictOnDelete();
            $table->foreignUuid('returned_by')->nullable()->constrained('users')->restrictOnDelete();
            $table->timestamp('borrowed_at');
            $table->timestamp('returned_at')->nullable();

            $table->enum('return_condition', ['good','damaged','needs_inspection',])->nullable();

            $table->text('borrowing_notes')->nullable();
            $table->text('return_notes')->nullable();

            $table->timestamps();

            $table->index('borrowed_at');
            $table->index('returned_at');
            $table->index(['technician_id', 'returned_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrowings');
    }
};
