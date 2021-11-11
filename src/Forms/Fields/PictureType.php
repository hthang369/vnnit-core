<?php
namespace Vnnit\Core\Forms\Fields;

class PictureType extends FormField
{
    protected function getTemplate()
    {
        return 'picture';
    }

    protected function getAttributes()
    {
        return [
            'attr' => array_filter([
                'class' => 'img-thumbnail',
                'width' => $this->getOption('width'),
                'height' => $this->getOption('height'),
            ]),
        ];
    }
}
