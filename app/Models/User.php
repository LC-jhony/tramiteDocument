<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Area;
use BezhanSalleh\FilamentShield\Facades\FilamentShield;
use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Filament\Models\Contracts\FilamentUser;
use BezhanSalleh\FilamentShield\Support\Utils;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, HasRoles, HasPanelShield;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'area_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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
    public function area(): BelongsTo
    {
        return $this->belongsTo(
            related: Area::class,
            foreignKey: 'area_id'
        );
    }
    protected static function booted(): void
    {
        if (config('filament-shield.area_user.enabled', true)) {
            FilamentShield::createRole(name: config('filament-shield.area_user.name', 'area_user'));
            static::creating(function (self $user) {
                $user->assignRole(config('filament-shield.area_user.name', 'area_user'));
            });
            static::creating(function (self $user) {
                $user->removeRole(config('filament-shield.area_user.name', 'area_user'));
            });
        }
    }
    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            return $this->hasRole(Utils::getSuperAdminName());
        } elseif ($panel->getId() === 'area') {
            return $this->hasRole(Utils::getSuperAdminName()) || $this->hasRole(config('filament-shield.area_user.name', 'area_user'));
        } else {
            return false;
        }
        // return $this->hasRole(config('filament-shield.super_admin.name')) || $this->hasRole(config('filament-shield.area_user.name', 'area_user'));
    }
}
