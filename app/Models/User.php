<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable //implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    protected $casts = [
        'dob' => 'date'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'dob',
        'mobile',
        'phone',
        'gender',
        'role',
        'organization_id',
        'role_id',
        'google_id',
        'google_token',
        'facebook_id',
        'facebook_token',
        'login_from',
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

    public function isSuperAdmin(){
        return $this->id === 1;
    }

    public function isAdmin(){
        return $this->role === 'admin';
    }

    public function isOrganization(){
        return $this->role === 'organization';
    }

    public function isOrganizationAdmin($id = 0){
        return $this->organization && $this->organization->user_id === ($id ? $id : auth()->id());
    }

    public function isStudent(){
        return $this->role === 'student';
    }

    public function isTutor(){
        return $this->role === 'tutor';
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function organization_role()
    {
        return $this->belongsTo(OrganizationRole::class, 'role_id');
    }
}
