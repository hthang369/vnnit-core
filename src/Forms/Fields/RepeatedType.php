<?php
namespace Vnnit\Core\Forms\Fields;

class RepeatedType extends FormField
{
    protected function getTemplate()
    {
        return 'button';
    }

    protected function getAttributes()
    {
        return [
            'class' => 'form-control',
        ];
    }
}
