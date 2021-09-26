<?php
namespace Vnnit\Core\Pagination;

use Illuminate\Pagination\UrlWindow;

class UrlPaginator extends UrlWindow
{
    private $numberOfPages;
    /**
     * Get the window of URLs to be shown.
     *
     * @return array
     */
    public function get()
    {
        $onEachSide = $this->paginator->onEachSide;
        $numberFirstPage = config('vnnit-core.pagination.numberFirstPage');
        $numberLastPage = config('vnnit-core.pagination.numberLastPage');

        $this->numberOfPages = ($onEachSide * 2) + $numberFirstPage + $numberLastPage + 1;

        if ($this->paginator->lastPage() <= $this->numberOfPages) {
            return $this->getSmallSlider();
        }

        return $this->getUrlSlider($onEachSide);
    }

    /**
     * Create a URL slider links.
     *
     * @param  int  $onEachSide
     * @return array
     */
    protected function getUrlSlider($onEachSide)
    {
        $window = $this->numberOfPages - 1;

        if (! $this->hasPages()) {
            return ['first' => null, 'slider' => null, 'last' => null];
        }

        // If the current page is very close to the beginning of the page range, we will
        // just render the beginning of the page range, followed by the last 2 of the
        // links in this list, since we will not have room to create a full slider.
        if ($this->currentPage() <= $window) {
            return $this->getSliderTooCloseToBeginning($window, 0);
        }

        // If the current page is close to the ending of the page range we will just get
        // this first couple pages, followed by a larger window of these ending pages
        // since we're too close to the end of the list to create a full on slider.
        elseif ($this->currentPage() > ($this->lastPage() - $window)) {
            return $this->getSliderTooCloseToEnding($window, 0);
        }

        // If we have enough room on both sides of the current page to build a slider we
        // will surround it with both the beginning and ending caps, with this window
        // of pages in the middle providing a Google style sliding paginator setup.
        return $this->getFullSlider($onEachSide);
    }

    /**
     * Get the starting URLs of a pagination slider.
     *
     * @return array
     */
    public function getStart()
    {
        $numberPrevPage = config('vnnit-core.pagination.numberFirstPage');
        return $this->paginator->getUrlRange(1, $numberPrevPage);
    }

    /**
     * Get the ending URLs of a pagination slider.
     *
     * @return array
     */
    public function getFinish()
    {
        $numberNextPage = config('vnnit-core.pagination.numberFirstPage') - 1;
        return $this->paginator->getUrlRange(
            $this->lastPage() - $numberNextPage,
            $this->lastPage()
        );
    }
}
