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
        return [
            'attr' => ['class' => 'btn btn-secondary'],
        ];
    }
}
