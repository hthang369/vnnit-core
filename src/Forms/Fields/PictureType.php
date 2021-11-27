<?php
namespace Vnnit\Core\Forms\Fields;

class PictureType extends FormField
{
    protected function getTemplate()
    {
        return 'picture';
    }

    protected function getAttributes(array $options = [])
    {
        data_set($options, 'class', array_merge(['picture'], data_get($options, 'field_attr.class', [])));
        return [
            'attr' => array_filter([
                'class' => 'img-thumbnail',
                'width' => data_get($options, 'width'),
                'height' => data_get($options, 'height'),
            ])
        ];
    }
}
