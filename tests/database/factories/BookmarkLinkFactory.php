<?php

namespace TomatoPHP\FilamentBookmarksMenu\Tests\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use TomatoPHP\FilamentBookmarksMenu\Models\BookmarkLink;

/**
 * @extends Factory<BookmarkLink>
 */
class BookmarkLinkFactory extends Factory
{
    protected $model = BookmarkLink::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->words(2, true),
            'url' => '/admin/'.$this->faker->unique()->slug(),
            'icon' => 'heroicon-s-link',
            'color' => 'primary',
        ];
    }
}
