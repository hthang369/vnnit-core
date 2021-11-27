<?php
namespace Vnnit\Core\Forms\Fields;

class CheckableType extends FormField
{
    protected function getTemplate()
    {
        return 'checkable';
    }

    protected function getAttributes(array $options = [])
    {
        return [
            'class' => 'form-control',
        ];
    }
}
