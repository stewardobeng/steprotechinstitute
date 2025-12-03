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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'affiliate_agent', 'student'])->default('student')->after('password');
            $table->string('profile_image')->nullable()->after('role');
            $table->string('phone')->nullable()->after('profile_image');
            $table->enum('status', ['active', 'inactive'])->default('active')->after('phone');
            $table->boolean('two_factor_enabled')->default(false)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'profile_image', 'phone', 'status', 'two_factor_enabled']);
        });
    }
};
