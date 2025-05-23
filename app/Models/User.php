<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable {
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        // 'username',
        'password',
        'email',
        'firstname',
        'lastname',
        'role_id',
        'date_of_birth',
        'avatar',
        'phone',
        'address',
        'created_time',
        'email_verified_at',
        'status'
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
    protected function casts(): array {
        return [
            'email_verified_at' => 'datetime:Y-m-d H:i:s',
            'created_time' => 'datetime:Y-m-d H:i:s',
            'password' => 'hashed',
        ];
    }

    public function wishlist() {
        return $this->hasOne(Wishlist::class);
    }

    public function branches() {
        return $this->hasMany(Branch::class, 'manager_id', 'id');
    }
}
