<?php
namespace Vnnit\Core\Forms\Fields;

class MapType extends FormField
{
    protected function getTemplate()
    {
        return 'map';
    }

    protected function getAttributes()
    {
        return [
            'attr' => ['class' => 'embed-responsive-item'],
        ];
    }

    /**
     * Default options for field.
     *
     * @return array
     */
    protected function getDefaults()
    {
        return ['tag' => 'iframe'];
    }
}
