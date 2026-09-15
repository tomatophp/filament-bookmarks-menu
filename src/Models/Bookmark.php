<?php

namespace TomatoPHP\FilamentBookmarksMenu\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * @property int $id
 * @property string|null $type
 * @property string $name
 * @property string|null $icon
 * @property string|null $color
 * @property bool|null $is_private
 * @property string|null $user_type
 * @property int|null $user_id
 * @property int|null $parent_id
 */
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
        'is_private' => 'boolean',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Bookmark::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Bookmark::class, 'parent_id');
    }

    public function links(): MorphToMany
    {
        return $this->morphedByMany(BookmarkLink::class, 'bookmarkable', 'bookmarkable', 'bookmark_id', 'bookmarkable_id');
    }

    public function user(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Public bookmarks plus the private bookmarks owned by the given user.
     */
    public function scopeVisibleTo(Builder $query, ?Authenticatable $user): Builder
    {
        return $query->where(function (Builder $query) use ($user): void {
            $query->where('is_private', false)->orWhereNull('is_private');

            if ($user) {
                $query->orWhere(function (Builder $query) use ($user): void {
                    $query->where('user_type', $user::class)
                        ->where('user_id', $user->getAuthIdentifier());
                });
            }
        });
    }

    public function isVisibleTo(?Authenticatable $user): bool
    {
        if (! $this->is_private) {
            return true;
        }

        return $user !== null
            && $this->user_type === $user::class
            && (string) $this->user_id === (string) $user->getAuthIdentifier();
    }
}
