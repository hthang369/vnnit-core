<?php
namespace Vnnit\Core\Traits\Grids;

use Illuminate\Support\Str;

trait ConfiguresGrid
{
    private function getViewName($name)
    {
        $prefix = config('vnnit-core.prefix');
        return "{$prefix}::components.grids.{$name}";
    }

    protected function getGridView()
    {
        return $this->getViewName('data-grid');
    }

    protected function getSearchView()
    {
        return $this->getViewName('search');
    }

    protected function getSearchParam()
    {
        return config('vnnit-core.search.param');
    }

    protected function getName() : string
    {
        return $this->name;
    }

    /**
     * the grid id
     * @return string
     */
    public function getId() : string
    {
        if (blank($this->id))
            $this->id = Str::singular(Str::camel($this->name)) . '-' . 'grid';

        return $this->id;
    }

    protected function getSearchPlaceholder()
    {
        if (empty($this->searchableColumns)) {
            $placeholder = Str::plural(Str::slug($this->getName()));

            return sprintf('search %s ...', $placeholder);
        }

        $placeholder = collect($this->searchableColumns)->implode(',');

        return sprintf('search %s by their %s ...', Str::lower($this->getName()), $placeholder);
    }

    protected function getSearchUrl(array $params = []): string
    {
        return route($this->getIndexRouteName(), $params);
    }

    protected function getIndexRouteName(): string
    {
        return $this->indexRouteName ?? $this->getSectionCode().'.index';
    }

    public function getSearchFormId()
    {
        return 'search-'.$this->getId();
    }
}
