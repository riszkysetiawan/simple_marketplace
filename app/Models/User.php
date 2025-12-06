<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\Transaction;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles, HasPanelShield;

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
        'provider',
        'provider_id',
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

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function role()
    {
        return $this->belongsTo(\Spatie\Permission\Models\Role::class, 'id_roles', 'id');
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super_admin');
    }

    public function isCustomer(): bool
    {
        return $this->hasRole('customer');
    }

    public function canAccessPanel(Panel $panel): bool
    {
        $panelId = $panel->getId();

        if ($panelId === 'admin') {
            return $this->hasRole('super_admin');
        }

        return false;
    }

    /**
     * ✅ Find or create user from Google
     */
    public static function findOrCreateFromGoogle($googleUser)
    {
        $email = $googleUser->email ?? $googleUser->getEmail();
        $name = $googleUser->name ?? $googleUser->getName();
        $id = $googleUser->id ?? $googleUser->getId();

        return self::firstOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'google_id' => $id,
                'provider' => 'google',
                'provider_id' => $id,
                'email_verified_at' => now(),
                'password' => bcrypt(Str::random(16)),
            ]
        );
    }

    /**
     * ✅ Find or create user from Facebook
     */
    public static function findOrCreateFromFacebook($facebookUser)
    {
        $email = $facebookUser->email ?? $facebookUser->getEmail();
        $name = $facebookUser->name ?? $facebookUser->getName();
        $id = $facebookUser->id ?? $facebookUser->getId();

        return self::firstOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'facebook_id' => $id,
                'provider' => 'facebook',
                'provider_id' => $id,
                'email_verified_at' => now(),
                'password' => bcrypt(Str::random(16)),
            ]
        );
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
