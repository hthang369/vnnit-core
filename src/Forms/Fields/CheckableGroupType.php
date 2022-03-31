<?php
namespace Vnnit\Core\Forms\Fields;

use Vnnit\Core\Forms\Form;

class CheckableGroupType extends FormField
{
    public function __construct($name, $type, Form $parent, array $options = [])
    {
        parent::__construct($name, $type, $parent, $options);
        $this->type = trim($this->type, '-group');
    }

    protected function getTemplate()
    {
        return 'checkable-group';
    }

    protected function getAttributes(array $options = [])
    {
        return [
            'attr' => ['class' => 'custom-control-input'],
            'checkable_label_attr' => ['class' => 'custom-control-label'],
            'wrapper_attr' => ['class' => 'custom-control custom-control-inline']
        ];
    }
}
