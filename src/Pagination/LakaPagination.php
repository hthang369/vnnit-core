<?php
namespace Vnnit\Core\Pagination;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\ComponentAttributeBag;

class LakaPagination extends LengthAwarePaginator
{
    private $nextIcon = 'fa fa-angle-right';
    private $prevIcon = 'fa fa-angle-left';

    public function __construct($items, $total, $perPage, $currentPage = null, array $options = [])
    {
        $navigationOpts = array_only($options, ['nextIcon', 'prevIcon']);
        $options = array_except($options, ['nextIcon', 'prevIcon']);
        parent::__construct($items, $total, $perPage, $currentPage, $options);

        foreach($navigationOpts as $key => $value) {
            $this->{$key} = $value;
        }

        $this->setDefaultView();
        $this->onEachSide(config('vnnit-core.pagination.onEachPage'));
    }

    public function setDefaultView($viewName = null)
    {
        if (blank($viewName)) {
            $prefix = config('vnnit-core.prefix');
            $defaultName = config('vnnit-core.components.pagination.view');
            $viewName = "{$prefix}::{$defaultName}";
        }
        static::defaultView($viewName);
    }

    /**
     * Get the array of elements to pass to the view.
     *
     * @return array
     */
    protected function elements()
    {
        $window = UrlPaginator::make($this);

        return array_filter([
            $window['first'],
            is_array($window['slider']) ? '...' : null,
            $window['slider'],
            is_array($window['last']) ? '...' : null,
            $window['last'],
        ]);
    }

    /**
     * Get the paginator links as a collection (for JSON responses).
     *
     * @return \Illuminate\Support\Collection
     */
    public function linkCollection()
    {
        $links = parent::linkCollection();
        $count = $links->count();
        $results = $links->transform(function($item, $key) use($count) {
            if ($key == 0 && !blank($this->prevIcon)) {
                data_set($item, 'label', '<i class="'.$this->prevIcon.'"></i>');
            } else if ($key == ($count - 1) && !blank($this->nextIcon)) {
                data_set($item, 'label', '<i class="'.$this->nextIcon.'"></i>');
            }
            return $item;
        });
        return collect([
            'first_link' => $results->first(),
            'elements' => $results->slice(1, $count - 2),
            'last_link' => $results->last()
        ]);
    }

    /**
     * Render the paginator using the given view.
     *
     * @param  string|null  $view
     * @param  array  $data
     * @return \Illuminate\Contracts\Support\Htmlable
     */
    public function render($view = null, $data = [])
    {
        return static::viewFactory()->make($view ?: static::$defaultView, array_merge($data, [
            'paginator' => $this,
            'links' => $this->linkCollection()->toArray(),
            'attributes' => new ComponentAttributeBag(),
            'attrs' => ['class' => 'pagination mb-0']
        ]));
    }
}
