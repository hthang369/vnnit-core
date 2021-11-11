<?php
namespace Vnnit\Core\Forms\Fields;

class StaticType extends FormField
{
    protected function getTemplate()
    {
        return 'static';
    }

    protected function getAttributes()
    {
        return [
            'attr' => ['class' => ['form-control-plaintext']],
        ];
    }

    /**
     * Default options for field.
     *
     * @return array
     */
    protected function getDefaults()
    {
        return ['tag' => 'div'];
    }
}
