<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'username',
        'password',
        'new_password',
        'email',
        'email_verified',
        'status',
        'last_login',
        'person_id'
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'new_password'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    public function person()
    {
        return $this->belongsTo(Person::class);
    }
    /**
     * Get all request-user records for this user.
     */
    public function requestUsers()
    {
        return $this->hasMany(RequestUser::class);
    }

    /**
     * Get all requests signed by this user through the pivot table.
     */
    public function signedRequests()
    {
        return $this->belongsToMany(Request::class, 'request_user')
            ->withPivot('role', 'status', 'signed_at')
            ->withTimestamps();
    }

    /**
     * Get all approved requests signed by this user.
     */
    public function approvedRequests()
    {
        return $this->belongsToMany(Request::class, 'request_user')
            ->withPivot('role', 'status', 'signed_at')
            ->wherePivot('status', 'approved')
            ->withTimestamps();
    }

    /**
     * Get all pending requests waiting for this user's signature.
     */
    public function pendingRequests()
    {
        return $this->belongsToMany(Request::class, 'request_user')
            ->withPivot('role', 'status', 'signed_at')
            ->wherePivot('status', 'pending')
            ->withTimestamps();
    }
}
