<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modification_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profile_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->json('data'); // tous les champs texte du profil
            $table->string('new_photo')->nullable();
            $table->text('motif_rejet')->nullable();
            $table->timestamps();
        });

        Schema::create('modification_request_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modification_request_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['cv', 'photo', 'legal', 'autre']);
            $table->string('path');
            $table->string('original_name');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('modification_request_documents');
        Schema::dropIfExists('modification_requests');
    }
};
