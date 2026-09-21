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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('phone');
            $table->string('email')->nullable();

            // Kept nullable so a specialist can be removed without losing the
            // patient's booking history. The specialty name is snapshotted as
            // free text for the same reason.
            $table->foreignId('specialist_id')->nullable()->constrained()->nullOnDelete();
            $table->string('specialty')->nullable();

            $table->string('referral_status')->default('yes');
            $table->date('preferred_date')->nullable();
            $table->string('preferred_time')->nullable();
            $table->text('notes')->nullable();

            $table->string('status')->default('new');
            $table->text('admin_notes')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
