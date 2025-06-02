<?php

declare(strict_types=1);

namespace Safemood\MagicScopes\Tests\Support\FakeResolvers;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Safemood\MagicScopes\Traits\HasMagicScopes;

class Post extends Model
{
    use HasFactory;
    use HasMagicScopes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'is_published',
        'has_featured_image',
        'is_sticky',
        'is_reviewed',
        'published_at',
        'reviewed_at',
        'status',
        'post_type',
        'visibility',
        'author_id',
        'category_id',
        'editor_id',
        'parent_post_id',
        'metadata',
        'settings',
        'tags',
        'views',
        'likes',
        'shares',
        'comments_count',
        'priority',
        'title',
        'slug',
        'content',
        'excerpt',
        'featured_image',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_published' => 'boolean',
        'has_featured_image' => 'boolean',
        'is_sticky' => 'boolean',
        'is_reviewed' => 'boolean',
        'published_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'metadata' => 'array',
        'settings' => 'array',
        'tags' => 'array',
        'priority' => 'decimal:2',
    ];

     
}
