<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use BezhanSalleh\FilamentShield\Support\Utils;
use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasRoles, HasFactory, Notifiable, HasPanelShield;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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
        ];
    }

    // relationship db
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'user_id');
    }
    public function salaries(): HasMany
    {
        return $this->hasMany(Salary::class, 'user_id');
    }

    public function leaves(): HasMany
    {
        return $this->hasMany(Leave::class, 'user_id');
    }

    //Middleware configuration for each panel based on roles
    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'employee') {
            return $this->hasRole(config('filament-shield.employee_user.name', 'Employee')) || $this->hasRole(config('filament-shield.hr_user.name', 'HR'));
        } elseif ($panel->getId() === 'hr') {
            return $this->hasRole(config('filament-shield.hr_user.name', 'HR'));
        }

        return $this->hasRole(Utils::getSuperAdminName() || $this->hasRole(Utils::getPanelUserRoleName()));
    }
}
