<?php

declare(strict_types=1);

namespace Workbench\App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = [
        'name',
        'email',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
