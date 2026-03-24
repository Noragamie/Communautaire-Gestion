<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('restrict');
            $table->string('bio')->nullable();
            $table->text('competences')->nullable();
            $table->text('experience')->nullable();
            $table->string('localisation')->nullable();
            $table->string('secteur_activite')->nullable();
            $table->string('photo')->nullable();
            $table->string('telephone')->nullable();
            $table->string('site_web')->nullable();
            $table->enum('niveau_etude', ['bac', 'licence', 'master', 'doctorat', 'autre'])->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'suspended'])->default('pending');
            $table->text('motif_rejet')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
