<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\JobMatchSeeder;
use App\Models\User;
use App\Models\Opportunite;
use App\Models\JobMatch;
use App\Models\Candidature;

class JobMatchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(JobMatchSeeder::class);
    }

    public function test_landing_page_renders_successfully_for_guests(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('JobMatch');
        $response->assertSee('Se connecter');
        $response->assertSee('Créer un compte');
    }

    public function test_login_page_renders_successfully(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Connexion à votre espace');
        $response->assertSee('Compte Démo');
    }

    public function test_register_page_renders_successfully(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertSee('Créer votre compte candidat');
    }

    public function test_unauthenticated_users_are_redirected_to_login(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');

        $response = $this->get('/profil');
        $response->assertRedirect('/login');
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $response = $this->post('/login', [
            'email' => 'candidat.demo@jobmatch.ai',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }

    public function test_user_can_register_new_account(): void
    {
        $response = $this->post('/register', [
            'name' => 'Sophie Laurent',
            'email' => 'sophie.laurent@example.com',
            'password' => 'password1234',
            'password_confirmation' => 'password1234',
        ]);

        $response->assertRedirect('/profil');
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'sophie.laurent@example.com',
            'name' => 'Sophie Laurent',
        ]);
    }

    public function test_authenticated_user_can_view_dashboard(): void
    {
        $user = User::where('email', 'candidat.demo@jobmatch.ai')->first();
        $this->assertNotNull($user);

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Candidat Démo');
        $response->assertSee('Opportunités Recommandées');
    }

    public function test_authenticated_user_can_view_profile_and_candidature(): void
    {
        $user = User::where('email', 'candidat.demo@jobmatch.ai')->first();
        $opp = Opportunite::first();

        $response = $this->actingAs($user)->get('/profil');
        $response->assertStatus(200);
        $response->assertSee('Mon Profil Candidat');

        $response = $this->actingAs($user)->get('/candidatures/opportunite/' . $opp->id);
        $response->assertStatus(200);
        $response->assertSee('Lettre de Motivation Personnalisée');
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::where('email', 'candidat.demo@jobmatch.ai')->first();

        $response = $this->actingAs($user)->post('/logout');
        $response->assertRedirect('/');
        $this->assertGuest();
    }

    public function test_user_can_update_search_preferences(): void
    {
        $user = User::where('email', 'candidat.demo@jobmatch.ai')->first();

        $response = $this->actingAs($user)->post('/profil/preferences', [
            'type_opportunite' => 'Chef de Projet Numérique',
            'pays' => 'Bénin',
            'types_contrat' => ['CDI', 'CDD'],
            'teletravail_only' => '1',
            'salaire_min' => '45000',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('profils_recherche', [
            'user_id' => $user->id,
            'type_opportunite' => 'Chef de Projet Numérique',
            'pays' => 'Bénin',
            'teletravail_only' => true,
        ]);

        $profileResponse = $this->actingAs($user)->get('/profil');
        $profileResponse->assertStatus(200);
        $profileResponse->assertSee('Chef de Projet Numérique');
        $profileResponse->assertSee('Bénin');
    }

    public function test_authenticated_user_can_view_candidatures_tracking_page(): void
    {
        $user = User::where('email', 'candidat.demo@jobmatch.ai')->first();

        $response = $this->actingAs($user)->get('/candidatures');
        $response->assertStatus(200);
        $response->assertSee('Suivi de mes Candidatures');
        $response->assertSee('Déposées');
        $response->assertSee('En Attente');
        $response->assertSee('Entretiens');
    }

    public function test_user_can_filter_and_update_candidature_status(): void
    {
        $user = User::where('email', 'candidat.demo@jobmatch.ai')->first();
        $candidature = Candidature::where('user_id', $user->id)->first();
        $this->assertNotNull($candidature);

        // Filter by tab deposees
        $response = $this->actingAs($user)->get('/candidatures?tab=deposees');
        $response->assertStatus(200);

        // Update status
        $statusResponse = $this->actingAs($user)->post("/candidatures/{$candidature->id}/status", [
            'statut' => 'entretien',
        ]);
        $statusResponse->assertRedirect();
        $statusResponse->assertSessionHas('success');

        $this->assertDatabaseHas('candidatures', [
            'id' => $candidature->id,
            'statut' => 'entretien',
        ]);
    }
}
