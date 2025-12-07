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
use Illuminate\Support\Facades\Log;

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
        try {
            $email = $googleUser->email;
            $name = $googleUser->name;
            $googleId = $googleUser->id;
            $avatar = $googleUser->avatar;

            Log::info('Finding/Creating Google User', [
                'email' => $email,
                'google_id' => $googleId
            ]);

            // Cari user berdasarkan google_id atau email
            $user = self::where('google_id', $googleId)
                ->orWhere('email', $email)
                ->first();

            if ($user) {
                Log::info('User found, updating Google ID if needed');

                // Update google_id jika belum ada
                if (!$user->google_id) {
                    $user->update([
                        'google_id' => $googleId,
                        'provider' => 'google',
                        'provider_id' => $googleId,
                        'avatar' => $avatar ?? $user->avatar,
                        'email_verified_at' => $user->email_verified_at ?? now(),
                    ]);
                }

                return $user;
            }

            Log::info('Creating new user from Google');

            // Buat user baru
            $user = self::create([
                'name' => $name,
                'email' => $email,
                'google_id' => $googleId,
                'provider' => 'google',
                'provider_id' => $googleId,
                'avatar' => $avatar,
                'email_verified_at' => now(),
                'password' => bcrypt(Str::random(16)),
            ]);

            // Assign role customer
            if (!$user->hasRole('customer')) {
                $user->assignRole('customer');
            }

            Log::info('User created successfully', ['id' => $user->id]);

            return $user;
        } catch (\Exception $e) {
            Log::error('Error in findOrCreateFromGoogle: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            throw $e;
        }
    }


    /**
     * Find or create user from Facebook
     */
    public static function findOrCreateFromFacebook($facebookUser)
    {
        try {
            $email = $facebookUser->email;
            $name = $facebookUser->name;
            $facebookId = $facebookUser->id;
            $avatar = $facebookUser->avatar;

            Log::info('Finding/Creating Facebook User', [
                'email' => $email,
                'facebook_id' => $facebookId
            ]);

            // Cari user berdasarkan facebook_id atau email
            $user = self::where('facebook_id', $facebookId)
                ->orWhere('email', $email)
                ->first();

            if ($user) {
                Log::info('User found, updating Facebook ID if needed');

                if (!$user->facebook_id) {
                    $user->update([
                        'facebook_id' => $facebookId,
                        'provider' => 'facebook',
                        'provider_id' => $facebookId,
                        'avatar' => $avatar ?? $user->avatar,
                        'email_verified_at' => $user->email_verified_at ?? now(),
                    ]);
                }

                return $user;
            }

            Log::info('Creating new user from Facebook');

            $user = self::create([
                'name' => $name,
                'email' => $email,
                'facebook_id' => $facebookId,
                'provider' => 'facebook',
                'provider_id' => $facebookId,
                'avatar' => $avatar,
                'email_verified_at' => now(),
                'password' => bcrypt(Str::random(16)),
            ]);

            if (!$user->hasRole('customer')) {
                $user->assignRole('customer');
            }

            Log::info('User created successfully', ['id' => $user->id]);

            return $user;
        } catch (\Exception $e) {
            Log::error('Error in findOrCreateFromFacebook: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            throw $e;
        }
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
