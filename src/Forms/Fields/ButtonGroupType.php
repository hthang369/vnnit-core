<?php
namespace Vnnit\Core\Forms\Fields;

class ButtonGroupType extends FormField
{
    protected function getTemplate()
    {
        return 'button';
    }

    protected function getAttributes(array $options = [])
    {
        return array_merge_recursive([
            'class' => 'form-control'
        ], $options);
    }
}
