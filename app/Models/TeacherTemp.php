<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeacherTemp extends Model
{
    use HasFactory;
    use SoftDeletes;
    public $table = 'teacher_temp';
    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
        'id_number',
        'type',
        'description',
        'accept',
        'section_id',
        'colloge_id',
    ];

}
