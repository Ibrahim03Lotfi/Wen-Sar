<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('category_business_rankings', function (Blueprint $table) {
            if (!Schema::hasColumn('category_business_rankings', 'manager_id')) {
                $table->foreignId('manager_id')
                    ->nullable()
                    ->after('business_id')
                    ->constrained('managers')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('category_business_rankings', 'manager_notified_at')) {
                $table->timestamp('manager_notified_at')->nullable()->after('expires_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('category_business_rankings', function (Blueprint $table) {
            if (Schema::hasColumn('category_business_rankings', 'manager_id')) {
                $table->dropConstrainedForeignId('manager_id');
            }

            if (Schema::hasColumn('category_business_rankings', 'manager_notified_at')) {
                $table->dropColumn('manager_notified_at');
            }
        });
    }
};
