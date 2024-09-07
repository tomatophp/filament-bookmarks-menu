<?php

namespace TomatoPHP\FilamentBookmarksMenu\Services\Contracts;

class BookmarkType
{
    public string $key;
    public string $label;
    public ?string $panel=null;

    public static function make(string $key): static
    {
        return (new static())->key($key);
    }

    public function key(string $key): static
    {
        $this->key = $key;
        return $this;
    }

    public function label(string $label): static
    {
        $this->label = $label;
        return $this;
    }

    public function panel(string $panel): static
    {
        $this->panel = $panel;
        return $this;
    }
}
