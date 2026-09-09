<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlogPostTranslation extends Model
{
    use HasFactory;

    protected $table = 'blog_post_translations';

    protected $fillable = [
        'blog_post_id',
        'locale',
        'slug',
        'title',
        'summary',
        'content',
        'tags',
    ];

    protected $casts = [
        'tags' => 'array',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(BlogPost::class, 'blog_post_id');
    }
}
