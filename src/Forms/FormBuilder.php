<?php
namespace Vnnit\Core\Forms;

class FormBuilder
{
    public function create($formClass, array $options = [], array $data = [])
    {
        if (!class_exists($formClass)) {
            throw new \InvalidArgumentException(
                'Form class with name ' . $formClass . ' does not exist.'
            );
        }
        $form = $this->setDependenciesAndOptions(resolve($formClass), $options, $data);
        $form->buildForm();

        return $form;
    }

    public function setDependenciesAndOptions($instance, array $options = [], array $data = [])
    {
        return $instance
            ->addData($data)
            ->setFormBuilder($this)
            ->setFormOptions($options);
    }
}
