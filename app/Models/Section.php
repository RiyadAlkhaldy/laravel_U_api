<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Section extends Model
{
    use HasFactory;
    protected $fillable = ['id','name','user_id','colloge_id' ];
    public function colloge(): BelongsTo
    {
        return $this->belongsTo(Colloge::class);
    }
    public function post(): HasMany
    {
        return $this->hasMany(Post::class);
    }

}
