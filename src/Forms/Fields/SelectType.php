<?php
namespace Vnnit\Core\Forms\Fields;

class SelectType extends FormField
{
    protected function getTemplate()
    {
        return 'select';
    }

    protected function getAttributes(array $options = [])
    {
        $attr = $this->getOption('attr');
        $attr = array_merge($attr, ['placeholder' => $options['empty_value']]);
        return [
            'attr' => $attr,
        ];
    }
}
