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
        Schema::table('businesses', function (Blueprint $table) {
            // Store detailed business hours as JSON
            // Structure: {
            //   "regular": {"open": "10:00", "close": "19:00"},
            //   "closed_days": ["friday"],
            //   "overrides": {
            //     "saturday": {"open": "09:00", "close": "22:00"}
            //   }
            // }
            $table->jsonb('business_hours')->nullable()->after('closing_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->dropColumn('business_hours');
        });
    }
};
