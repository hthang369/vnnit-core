<?php
namespace Vnnit\Core\Traits\Grids;

trait RendersGrid
{
    public function __toString()
    {
        return $this->toHtml();
    }

    public function toHtml()
    {
        return $this->render();
    }

    /**
     * Render the grid as HTML on the user defined view
     *
     * @return string
     * @throws \Throwable
     */
    public function render()
    {
        $data = [
            'grid' => $this,
            'data' => $this->resultData,
            'sectionCode' => $this->getSectionCode()
        ];
        return view($this->getGridView(), $data)->render();
    }

    /**
     * Render the search form on the grid
     *
     * @return string
     * @throws \Throwable
     */
    public function renderSearchForm()
    {
        $params = func_get_args();
        $data = [
            // 'colSize' => $this->getGridToolbarSize()[0], // size
            'action' => $this->getSearchUrl(),
            'id' => $this->getSearchFormId(),
            'name' => $this->getSearchParam(),
            // 'dataAttributes' => [],
            'placeholder' => $this->getSearchPlaceholder(),
        ];

        return view($this->getSearchView(), $data)->render();
    }
}
