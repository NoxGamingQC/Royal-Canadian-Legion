<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

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

    public function getUserBranch(): string
    {
        $branch = $this->join('branches', 'users.branch_id', '=', 'branches.id')->first(['branches.branch_id']);
        return sprintf("%03d", $branch->branch_id);
    }

    public function getUserCommand(): string
    {
        $command = $this->join('branches', 'users.branch_id', '=', 'branches.id')->first(['branches.command']);
        return sprintf("%02d", $command->command);
    }

    public function scopeHasPermission($query, $permission)
    {
        $userPermission = explode(';', $this->access);
        if (in_array('owner', $userPermission)) {
            return true;
        }
        return in_array($permission, $userPermission);
    }
}
