<?php

declare(strict_types=1);

namespace Workbench\App\Models;

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

    /**
     * Relationships
     */
    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'editor_unformal_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function parentPost()
    {
        return $this->belongsTo(Post::class, 'parent_post_id');
    }

    public function childPosts()
    {
        return $this->hasMany(Post::class, 'parent_post_id');
    }

    /**
     * Scope a query to only include recent posts (published in last 7 days).
     */
    public function scopeRecent($query)
    {
        return $query->where('published_at', '>=', now()->subDays(7));
    }
}
