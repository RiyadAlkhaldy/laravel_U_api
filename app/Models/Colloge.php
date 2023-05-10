<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Colloge extends Model
{
    use HasFactory;
    protected $fillable = ['id','name','user_id'  ];
    public function section(): HasMany
    {
        return $this->hasMany(Section::class);
    }
    public function post(): HasMany
    {
        return $this->hasMany(Post::class);
    }

}
