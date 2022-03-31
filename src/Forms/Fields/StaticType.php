<?php
namespace Vnnit\Core\Forms\Fields;

class StaticType extends FormField
{
    protected function getTemplate()
    {
        return 'static';
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
        return ['tag' => 'div', 'attr' => ['class' => ['form-control-plaintext']]];
    }
}
