<?php
namespace Vnnit\Core\Forms\Fields;

use Vnnit\Core\Forms\Field;

class InputType extends FormField
{
    protected function getTemplate()
    {
        return 'input';
    }

    protected function getAttributes(array $options = [])
    {
        return [
            'label_show' => !str_is($this->type, Field::HIDDEN),
        ];
    }
}
