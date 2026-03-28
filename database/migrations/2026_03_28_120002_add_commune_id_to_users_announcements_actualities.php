<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('commune_id')->nullable()->after('role')->constrained('communes')->nullOnDelete();
        });

        Schema::table('announcements', function (Blueprint $table) {
            $table->foreignId('commune_id')->nullable()->after('user_id')->constrained('communes')->nullOnDelete();
        });

        Schema::table('actualities', function (Blueprint $table) {
            $table->foreignId('commune_id')->nullable()->after('user_id')->constrained('communes')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('actualities', function (Blueprint $table) {
            $table->dropConstrainedForeignId('commune_id');
        });
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropConstrainedForeignId('commune_id');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('commune_id');
        });
    }
};
