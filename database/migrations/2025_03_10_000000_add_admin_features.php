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
        // Add role column to users if it doesn't exist
        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role')->default('user')->after('password');
            });
        }

        // Add has_prilohy to categories
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
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('role');
            });
        }

        if (Schema::hasTable('categories') && Schema::hasColumn('categories', 'has_prilohy')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropColumn('has_prilohy');
            });
        }
    }
};
