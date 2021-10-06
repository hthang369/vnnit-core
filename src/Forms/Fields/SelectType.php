<?php
namespace Vnnit\Core\Forms\Fields;

class SelectType extends FormField
{
    protected function getTemplate()
    {
        return 'select';
    }

    protected function getAttributes()
    {
        $attr = $this->getOption('attr');
        $attr = array_merge($attr, ['placeholder' => $this->getOption('empty_value')]);
        return [
            'attr' => $attr,
        ];
    }
}
