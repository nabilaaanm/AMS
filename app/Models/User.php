<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Region;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Specify the table name.
     *
     * @var string
     */
    protected $table = 'users'; // Set the custom table name

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'mobile_number',
        'region',
        'role'
    ];
    public $timestamps = false;

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
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'mobile_number' => 'integer',
        'role' => 'integer',
    ];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}