<?php

namespace TomatoPHP\FilamentBookmarksMenu\Tests\Database\Factories;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\Factory;
use TomatoPHP\FilamentBookmarksMenu\Models\Bookmark;

/**
 * @extends Factory<Bookmark>
 */
class BookmarkFactory extends Factory
{
    protected $model = Bookmark::class;

    public function definition(): array
    {
        return [
            'type' => 'folder',
            'name' => $this->faker->unique()->words(2, true),
            'icon' => 'heroicon-s-folder',
            'color' => null,
            'is_private' => false,
        ];
    }

    public function privateFor(Authenticatable $user): static
    {
        return $this->state(fn (): array => [
            'is_private' => true,
            'user_type' => $user::class,
            'user_id' => $user->getAuthIdentifier(),
        ]);
    }
}
