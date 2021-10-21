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
            'attr' => ['class' => 'img-thumbnail'],
        ];
    }
}
