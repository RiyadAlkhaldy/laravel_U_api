<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    
    public $fillable = [
        'id', 'name', 'university_id', 'level','section','id_number',
    ];
public $timestamps = false;
}
