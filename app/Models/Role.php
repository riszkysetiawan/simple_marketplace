<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
    ];

    // ===== RELATIONSHIPS =====

    /**
     * Role has many Users
     */
    public function users()
    {
        return $this->hasMany(User::class, 'id_roles', 'id');
    }

    // ===== HELPER METHODS =====

    /**
     * Check if this is super admin role
     */
    public function isSuperAdmin(): bool
    {
        return $this->name === 'super_admin';
    }

    /**
     * Check if this is customer role
     */
    public function isCustomer(): bool
    {
        return $this->name === 'customer';
    }

    /**
     * Get users count for this role
     */
    public function getUsersCount(): int
    {
        return $this->users()->count();
    }
}
