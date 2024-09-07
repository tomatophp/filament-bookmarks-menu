<?php

namespace TomatoPHP\FilamentBookmarksMenu\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Bookmark extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'name',
        'icon',
        'color',
        'is_private',
        'user_type',
        'user_id',
        'parent_id',
    ];

    protected $casts = [
        "is_private" => "boolean",
    ];

    /**
     * @return BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Bookmark::class, 'parent_id');
    }

    /**
     * @return HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(Bookmark::class, 'parent_id');
    }


    public function links()
    {
        return $this->morphedByMany(BookmarkLink::class, 'bookmarkable', 'bookmarkable', 'bookmark_id', 'bookmarkable_id');
    }

    /**
     * @return MorphTo
     */
    public function user(): MorphTo
    {
        return $this->morphTo();
    }
}
