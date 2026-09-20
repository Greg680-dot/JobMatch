<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Sources de collecte
        Schema::create('sources', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('type_source'); // 'api', 'rss', 'scraper'
            $table->string('url_cible')->nullable();
            $table->json('config_selectors')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_run_at')->nullable();
            $table->timestamps();
        });

        // 2. Opportunités d'emploi collectées
        Schema::create('opportunites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('source_id')->nullable()->constrained('sources')->nullOnDelete();
            $table->string('titre');
            $table->string('entreprise');
            $table->string('localisation')->default('France / Télétravail');
            $table->string('type_contrat')->default('CDI');
            $table->text('description');
            $table->decimal('salaire_indicatif', 10, 2)->nullable();
            $table->boolean('teletravail')->default(false);
            $table->string('url_source')->nullable();
            $table->string('deduplication_hash')->unique();
            $table->json('embedding')->nullable();
            $table->string('date_publication')->nullable();
            $table->timestamps();
        });

        // 3. CVs téléversés et analysés
        Schema::create('cvs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('original_filename');
            $table->text('raw_text')->nullable();
            $table->json('parsed_data');
            $table->json('embedding')->nullable();
            $table->boolean('is_default')->default(true);
            $table->timestamps();
        });

        // 4. Profils et objectifs de recherche
        Schema::create('profils_recherche', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->json('types_contrat')->nullable();
            $table->json('localisations')->nullable();
            $table->decimal('salaire_min', 10, 2)->nullable();
            $table->json('keywords_must')->nullable();
            $table->json('keywords_excluded')->nullable();
            $table->boolean('teletravail_only')->default(false);
            $table->timestamps();
        });

        // 5. Scores de Matching
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('opportunite_id')->constrained('opportunites')->cascadeOnDelete();
            $table->decimal('score_pertinence', 5, 2);
            $table->boolean('passed_hard_filters')->default(true);
            $table->string('rejection_reason')->nullable();
            $table->json('matching_skills')->nullable();
            $table->json('missing_skills')->nullable();
            $table->text('summary_explanation')->nullable();
            $table->enum('statut', ['nouveau', 'favori', 'ignore', 'en_cours'])->default('nouveau');
            $table->timestamps();
        });

        // 6. Candidatures générées et suivies
        Schema::create('candidatures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('opportunite_id')->constrained('opportunites')->cascadeOnDelete();
            $table->foreignId('match_id')->nullable()->constrained('matches')->nullOnDelete();
            $table->string('objet_email')->nullable();
            $table->text('lettre_motivation');
            $table->json('cv_adaptation_tips')->nullable();
            $table->json('suggested_skills')->nullable();
            $table->enum('statut', ['brouillon', 'validee', 'envoyee', 'relancee', 'entretien', 'refusee', 'acceptee'])->default('brouillon');
            $table->string('mode_envoi')->default('email'); // 'email' ou 'web_form'
            $table->timestamp('date_envoi')->nullable();
            $table->text('notes_candidat')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidatures');
        Schema::dropIfExists('matches');
        Schema::dropIfExists('profils_recherche');
        Schema::dropIfExists('cvs');
        Schema::dropIfExists('opportunites');
        Schema::dropIfExists('sources');
    }
};
