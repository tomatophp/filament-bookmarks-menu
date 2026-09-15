# Changelog

## v5.0.0

- Support Filament v5, Livewire 4 and Laravel 12 / 13 (PHP 8.2+). Fixes the Composer install error on newer Filament versions (#2).
- The previous Filament v3 line lives on the `v3` branch.
- Sidebar render hook is now registered per panel (`PanelsRenderHook::SIDEBAR_NAV_END`) and uses Filament's own sidebar item markup.
- Private bookmark collections are scoped to their owner: hidden from other users' sidebars, their page returns 404, and other users cannot attach links to them or remove links from them.
- Bookmark actions no longer remove links saved by other users; removing a link only detaches it from the collections you can see.
- Escape bookmark names in the sidebar tooltip.
- `filament-bookmarks-menu:install` runs the package migrations in-process.
- Removed the no-op `nwidart/laravel-modules` detection.
- Added a Pest + Testbench test suite and GitHub Actions workflow.
