<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('actualities', function (Blueprint $table) {
            $table->text('image_data')->nullable()->after('image');
        });

        Schema::table('announcements', function (Blueprint $table) {
            $table->text('image_data')->nullable()->after('image');
        });

        Schema::table('profiles', function (Blueprint $table) {
            $table->text('photo_data')->nullable()->after('photo');
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->text('file_data')->nullable()->after('path');
        });

        Schema::table('modification_requests', function (Blueprint $table) {
            $table->text('new_photo_data')->nullable()->after('new_photo');
        });

        Schema::table('modification_request_documents', function (Blueprint $table) {
            $table->text('file_data')->nullable()->after('path');
        });
    }

    public function down(): void
    {
        Schema::table('actualities', function (Blueprint $table) {
            $table->dropColumn('image_data');
        });

        Schema::table('announcements', function (Blueprint $table) {
            $table->dropColumn('image_data');
        });

        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn('photo_data');
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn('file_data');
        });

        Schema::table('modification_requests', function (Blueprint $table) {
            $table->dropColumn('new_photo_data');
        });

        Schema::table('modification_request_documents', function (Blueprint $table) {
            $table->dropColumn('file_data');
        });
    }
};
