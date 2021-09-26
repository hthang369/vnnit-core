<?php

namespace Vnnit\Core\Components\Tables;

use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\UrlWindow;
use Illuminate\Support\Arr;
use Vnnit\Core\Components\Component;
use Vnnit\Core\Helpers\Classes;
use Vnnit\Core\Pagination\LakaPagination;
use Vnnit\Core\Pagination\UrlPaginator;

class Pagination extends Component
{
    public $attrs;
    public $current;
    public $total;
    public $next;
    public $prev;
    public $pages;
    public $links;
    private $startPage = 1;
    private $numberOfPage = 5;
    private $firstNumber = false;
    private $lastNumber = false;
    public $except = [];
    public $paginator;
    private $pageName;
    private $items;

    /**
     * The component alias name.
     *
     * @var string
     */
    public $componentName = 'pagination';

    public function __construct(
        $current = '',
        $total = '',
        $next = '',
        $prev = '',
        $pages = '',
        $class = '',
        $except = [],
        $limit = null,
        $pageName = null,
        $items = []
    )
    {
        $this->current = $current ?: 0;
        $this->total = $total ?: 0;
        $this->pages = $pages ?: 0;
        $this->except = $except ?: [];
        $this->limit = $limit ?? config('vnnit-core.pagination.perPage');
        $this->next = $next ?: '';
        $this->prev = $prev ?: '';
        $this->attrs['class'] = Classes::get([
            'pagination',
            'mb-0',
            'class' => $class ?: '',
        ]);
        $this->pageName = $pageName ?? 'page';
        $this->items = $items;
        $this->attrs = \array_filter($this->attrs);
        $this->next = 'fa fa-angle-right';
        $this->prev = 'fa fa-angle-left';
        $this->links = $this->getLinks();
    }

    private function getLinks()
    {
        $this->paginator = new LakaPagination($this->items, $this->total, $this->limit, (int)$this->current, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => $this->pageName,
            'nextIcon' => $this->next,
            'prevIcon' => $this->prev
        ]);
        $query = request()->except($this->except);
        $this->paginator->appends($query);
        return $this->paginator->linkCollection();
    }
}
