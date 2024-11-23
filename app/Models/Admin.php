<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $table = 'admin';
    protected $primaryKey = 'id_Admin';

    protected $fillable = [
        'username',
        'phoneNumber',
        'email',
        'password',
        'is_Active',
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
        // 'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public $timestamps = true;

    const CREATED_AT = 'created_At';
    const UPDATED_AT = 'updated_At';

}
