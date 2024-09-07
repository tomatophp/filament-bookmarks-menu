<?php

namespace TomatoPHP\FilamentBookmarksMenu\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use TomatoPHP\FilamentBookmarksMenu\Traits\HasBookmarks;

class BookmarkLink extends Model
{
    use HasFactory;
    use HasBookmarks;

    protected $fillable = [
        'name',
        'url',
        'icon',
        'color',
    ];

}
