<?php

namespace TomatoPHP\FilamentBookmarksMenu\Traits;

trait HasPage
{
    public string $page = 'edit';

    public function page(string $page): static
    {
        $this->page = $page;
        return $this;
    }

    public function getPage(): string
    {
        return $this->page;
    }
}
