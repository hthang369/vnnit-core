<?php
namespace Vnnit\Core\Forms\Fields;

use Vnnit\Core\Forms\Form;

class SelectType extends FormField
{
    public function __construct($name, $type, Form $parent, array $options = [])
    {
        $newType = $type;
        $default_opts = [];
        if ($type == 'multi-select') {
            $newType = 'select';
            $default_opts = ['multiple' => 'multiple'];
        }
        parent::__construct($name, $newType, $parent, array_merge($options, $default_opts));
    }

    protected function getTemplate()
    {
        return 'select';
    }

    protected function getAttributes(array $options = [])
    {
        $attr = $this->getOption('attr');
        if (isset($options['empty_value'])) {
            $attr = array_merge($attr, ['placeholder' => $options['empty_value']]);
            unset($options['empty_value']);
        }
        $attr = array_merge($attr, array_only($options, ['multiple', 'id']));
        return [
            'attr' => $attr,
        ];
    }
}
