<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens as SanctumHasApiTokens;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use BezhanSalleh\FilamentShield\Traits\HasPanelShield;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles, HasPanelShield;  // ✅ Add HasPanelShield

    protected $guard_name = 'web';

    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'facebook_id',
        'avatar',
        'phone',
        'address',
        'id_roles',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'id_roles' => 'integer',
        ];
    }

    // ===== DIRECT RELATION =====

    public function role()
    {
        return $this->belongsTo(\Spatie\Permission\Models\Role::class, 'id_roles', 'id');
    }

    // ===== HELPER METHODS =====

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super_admin');
    }

    public function isCustomer(): bool
    {
        return $this->hasRole('customer');
    }

    // ===== FILAMENT PANEL ACCESS =====

    public function canAccessPanel(Panel $panel): bool
    {
        $panelId = $panel->getId();

        // Admin panel - only super_admin
        if ($panelId === 'admin') {
            return $this->hasRole('super_admin');
        }

        // Customer panel - only customer
        if ($panelId === 'customer') {
            return $this->hasRole('customer');
        }

        return false;
    }

    // ===== RELATIONSHIPS =====

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
