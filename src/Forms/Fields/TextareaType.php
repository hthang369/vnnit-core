<?php
namespace Vnnit\Core\Forms\Fields;

class TextareaType extends FormField
{
    protected function getTemplate()
    {
        return 'textarea';
    }

    protected function getAttributes()
    {
        return [
            'class' => 'form-control',
        ];
    }
}