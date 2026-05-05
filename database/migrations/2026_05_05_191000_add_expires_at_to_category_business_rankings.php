<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('category_business_rankings', function (Blueprint $table) {
            $table->timestamp('expires_at')->nullable()->after('rank');
        });
    }

    public function down(): void
    {
        Schema::table('category_business_rankings', function (Blueprint $table) {
            $table->dropColumn('expires_at');
        });
    }
};
