<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'is_admin'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    /**
     * Vérifie si l'utilisateur possède les droits administrateur.
     */
    public function isAdmin(): bool
    {
        if ($this->is_admin) {
            return true;
        }

        $adminEmail = env('ADMIN_EMAIL');
        $defaultEmail = env('DEFAULT_USER_EMAIL');

        if (!empty($adminEmail) && strcasecmp($this->email, $adminEmail) === 0) {
            return true;
        }

        if (!empty($defaultEmail) && strcasecmp($this->email, $defaultEmail) === 0) {
            return true;
        }

        // Par défaut pour le compte démonstration préconfiguré
        if (strcasecmp($this->email, 'candidat.demo@jobmatch.ai') === 0) {
            return true;
        }

        return false;
    }
}
