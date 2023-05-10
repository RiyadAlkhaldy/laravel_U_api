<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Like extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'post_id',
        'user_id',
        'created_at',
        'updated_at',
        
    ];
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
    
}
