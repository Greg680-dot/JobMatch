<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profils_recherche', function (Blueprint $table) {
            $table->string('type_opportunite')->nullable()->after('user_id');
            $table->string('pays')->nullable()->after('type_opportunite');
        });
    }

    public function down(): void
    {
        Schema::table('profils_recherche', function (Blueprint $table) {
            $table->dropColumn(['type_opportunite', 'pays']);
        });
    }
};
