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
        Schema::table('specialists', function (Blueprint $table) {
            // Ordered content blocks for the public profile page. Each entry is
            // ['heading' => ?string, 'type' => 'list'|'paragraphs', 'items' => string[]],
            // which lets one doctor list services while another writes prose.
            $table->json('profile_sections')->nullable()->after('special_interests');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('specialists', function (Blueprint $table) {
            $table->dropColumn('profile_sections');
        });
    }
};
