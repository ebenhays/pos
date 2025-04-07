<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    public function canAccessPanel(\Filament\Panel $panel): bool
    {
        // Allow access only if the user is not disabled
        //return !$this->disabled;
        return true;
    }
    protected $fillable = [
        'name',
        'email',
        'password',
        'disabled'
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
            'disabled' => 'bool'
        ];
    }

    public function dailyTransactions(): HasMany
    {
        return $this->hasMany(DailyTransaction::class, 'user_id');
    }

    public function getFullNameAttribute()
    {
        return $this->name;
    }
}
