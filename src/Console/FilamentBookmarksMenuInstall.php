<?php

namespace TomatoPHP\FilamentBookmarksMenu\Console;

use Illuminate\Console\Command;

class FilamentBookmarksMenuInstall extends Command
{
    /**
     * @var string
     */
    protected $name = 'filament-bookmarks-menu:install';

    /**
     * @var string
     */
    protected $description = 'install package and publish assets';

    public function handle(): int
    {
        $this->info('Running Filament Bookmarks Menu migrations');

        $this->call('migrate', ['--force' => true]);

        $this->info('Filament Bookmarks Menu installed successfully.');

        return self::SUCCESS;
    }
}
