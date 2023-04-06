<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'type',
        'phone',
        'zip_code',
        'address',
        'number',
        'district',
        'city',
        'state',
        'complement',
        'email',
        'password',
        'image',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function permissions ()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function hasPermissions ($value)
    {
        $userPermission = $this->permissions;
        return $value->intersect($userPermission)->count();
    }

    public function permission_user () {
        return $this->hasMany(PermissionUsers::class);
    }

    public function has_permission_user($collection) {

        $permissions_user = $this->permission_user;

        return $collection->intersect($permissions_user)->count();
    }

    public function leads ()
    {
        return $this->hasMany(Lead::class);
    }

    public function lawyers ()
    {
        return $this->hasMany(Lawyer::class);
    }

    public function administratives()
    {
        return $this->hasMany(Administrative::class);
    }
}