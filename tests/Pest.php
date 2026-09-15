<?php

use Filament\Facades\Filament;
use TomatoPHP\FilamentBookmarksMenu\Tests\TestCase;

uses(TestCase::class)
    ->beforeEach(fn () => Filament::setCurrentPanel(Filament::getPanel('admin')))
    ->in(__DIR__);
