<?php
namespace Vnnit\Core\Forms\Fields;

class MapType extends FormField
{
    protected function getTemplate()
    {
        return 'map';
    }

    protected function getAttributes(array $options = [])
    {
        return $options;
    }

    /**
     * Default options for field.
     *
     * @return array
     */
    protected function getDefaults()
    {
        return ['tag' => 'iframe', 'attr' => ['class' => 'embed-responsive-item']];
    }
}
