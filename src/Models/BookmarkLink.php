<?php

namespace TomatoPHP\FilamentBookmarksMenu\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use TomatoPHP\FilamentBookmarksMenu\Traits\HasBookmarks;

/**
 * @property int $id
 * @property string $url
 * @property string $name
 * @property string|null $icon
 * @property string|null $color
 */
class BookmarkLink extends Model
{
    use HasBookmarks;
    use HasFactory;

    protected $fillable = [
        'name',
        'url',
        'icon',
        'color',
    ];

    /**
     * Links saved in at least one bookmark the given user can see.
     */
    public function scopeVisibleTo(Builder $query, ?Authenticatable $user): Builder
    {
        return $query->whereHas('bookmarks', fn (Builder $query) => $query->visibleTo($user));
    }

    public static function findVisibleByUrl(string $url, ?Authenticatable $user): ?self
    {
        return static::query()->where('url', $url)->visibleTo($user)->first();
    }

    /**
     * Save the url into the given bookmarks, ignoring bookmarks the user cannot see.
     *
     * @param  array{name: string, icon?: string|null, color?: string|null}  $attributes
     * @param  array<int, int|string>|int|string  $bookmarkIds
     */
    public static function saveToBookmarks(string $url, array $attributes, array|int|string $bookmarkIds, ?Authenticatable $user): self
    {
        $link = static::query()->firstOrCreate(['url' => $url], $attributes);

        $allowedIds = Bookmark::query()
            ->visibleTo($user)
            ->whereIn('id', (array) $bookmarkIds)
            ->pluck('id')
            ->all();

        $link->bookmarks()->syncWithoutDetaching($allowedIds);

        return $link;
    }

    /**
     * Remove the link from the bookmarks the user can see, deleting it once no bookmark holds it.
     */
    public function removeFromBookmarksVisibleTo(?Authenticatable $user): void
    {
        $this->bookmarks()->detach(
            Bookmark::query()->visibleTo($user)->pluck('id')->all()
        );

        if ($this->bookmarks()->count() === 0) {
            $this->delete();
        }
    }
}
