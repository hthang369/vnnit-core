<?php
namespace Vnnit\Core\Forms\Fields;

class EntityType extends FormField
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
