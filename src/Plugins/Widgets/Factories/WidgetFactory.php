<?php
namespace Vnnit\Core\Plugins\Widgets\Factories;

class WidgetFactory extends AbstractWidgetFactory
{
    /**
     * Run widget without magic method.
     *
     * @return mixed
     */
    public function run(...$params)
    {
        $this->instantiateWidget($params);
        return $this->render();
    }
}
