<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;
    public $table = 'Profile';
    public $fillable = [
        'id', 'first_name', 'last_name', 'gender', 'birthday', 'city', 'region', 'neighborhood', 'type', 'description', 'img', 'profile_collage', 'profile_section', 'level'
    ];
public $timestamps = false;
}