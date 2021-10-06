<?php
namespace Vnnit\Core\Forms\Fields;

class CollectionType extends FormField
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
