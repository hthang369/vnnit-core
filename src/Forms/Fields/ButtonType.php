<?php
namespace Vnnit\Core\Forms\Fields;

class ButtonType extends FormField
{
    protected function getTemplate()
    {
        return 'button';
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
        return ['attr' => ['class' => 'btn btn-secondary']];
    }
}
