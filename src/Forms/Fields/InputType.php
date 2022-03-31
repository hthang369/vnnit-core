<?php
namespace Vnnit\Core\Forms\Fields;

use Vnnit\Core\Forms\Field;
use Vnnit\Core\Forms\Form;

class InputType extends FormField
{
    public function __construct($name, $type, Form $parent, array $options = [])
    {
        $newType = $type;
        $default_opts = [];
        if ($type == 'multi-file') {
            $newType = 'file';
            $default_opts = ['multiple' => 'multiple'];
        }
        parent::__construct($name, $newType, $parent, array_merge($options, $default_opts));
    }

    protected function getTemplate()
    {
        return 'input';
    }

    protected function getAttributes(array $options = [])
    {
        return [
            'label_show' => !str_is($this->type, Field::HIDDEN),
            'attr' => array_merge(['class' => [$this->parent->getConfig('defaults.field_class')]], $options),
        ];
    }
}
