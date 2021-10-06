<?php
namespace Vnnit\Core\Forms\Fields;

class CheckableType extends FormField
{
    protected function getTemplate()
    {
        return 'input';
    }

    protected function getAttributes()
    {
        return [
            'class' => 'form-control',
        ];
    }
}
