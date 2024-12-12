<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;
    protected $table = 'profiles';

    protected $fillable = [
        'company_name',
        'logo_path',
        'description',
        'address',
        'maps',
        'instagram_link',
        'whatsapp_link',
        'phone_number',
        'email'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
}
