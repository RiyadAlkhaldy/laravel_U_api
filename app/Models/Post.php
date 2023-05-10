<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'content',
        'type',
        'url',
        'user_id',
        'section_id',
        'colloge_id',
        'created_at',
        'updated_at',
        

    ];
    public function comment(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
    // public function scopeNumberComments($query) 
    // {
    //     return $query->  count($this->comment()->id);
    // }
    public function like(): HasMany
    {
        return $this->hasMany(Like::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function colloge(): BelongsTo
    {
        return $this->belongsTo(Colloge::class);
    }
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }
    

}