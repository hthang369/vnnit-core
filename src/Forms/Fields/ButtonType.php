<?php
namespace Vnnit\Core\Forms\Fields;

class ButtonType extends FormField
{
    protected function getTemplate()
    {
        return 'page_or_link';
    }

    protected function getAttributes()
    {
        return [
            'class' => 'form-control',
        ];
    }
}
