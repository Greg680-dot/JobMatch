<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visites', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45)->nullable();
            $table->string('country', 100)->default('Inconnu');
            $table->string('country_code', 10)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('device_type', 30)->default('Ordinateur');
            $table->string('os', 50)->default('Autre');
            $table->string('browser', 50)->default('Autre');
            $table->string('path', 255)->default('/');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->index(['created_at', 'device_type']);
            $table->index('country');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visites');
    }
};