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
        Schema::table('company_intro_heroes', function (Blueprint $table) {
            if (!Schema::hasColumn('company_intro_heroes', 'show_badge')) {
                $table->boolean('show_badge')->default(true)->after('badge');
            }
            if (!Schema::hasColumn('company_intro_heroes', 'show_banner')) {
                $table->boolean('show_banner')->default(true)->after('is_active');
            }
        });

        Schema::table('hero_slides', function (Blueprint $table) {
            if (!Schema::hasColumn('hero_slides', 'show_badge')) {
                $table->boolean('show_badge')->default(true)->after('badge_text');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_intro_heroes', function (Blueprint $table) {
            if (Schema::hasColumn('company_intro_heroes', 'show_badge')) {
                $table->dropColumn('show_badge');
            }
            if (Schema::hasColumn('company_intro_heroes', 'show_banner')) {
                $table->dropColumn('show_banner');
            }
        });

        Schema::table('hero_slides', function (Blueprint $table) {
            if (Schema::hasColumn('hero_slides', 'show_badge')) {
                $table->dropColumn('show_badge');
            }
        });
    }
};
