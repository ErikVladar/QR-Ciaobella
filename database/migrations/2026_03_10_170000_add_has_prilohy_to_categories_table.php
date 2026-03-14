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
        if (Schema::hasTable('categories') && !Schema::hasColumn('categories', 'has_prilohy')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->boolean('has_prilohy')->default(false)->after('image_path');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('categories') && Schema::hasColumn('categories', 'has_prilohy')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropColumn('has_prilohy');
            });
        }
    }
};
