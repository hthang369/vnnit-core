<?php

/*
 * You can place your custom package configuration in here.
 */

use Laka\Core\Components\Common\Alert;
use Laka\Core\Components\Common\Badge;
use Laka\Core\Components\Common\Breadcrumb;
use Laka\Core\Components\Common\Button;
use Laka\Core\Components\Common\Card;
use Laka\Core\Components\Common\CardFooter;
use Laka\Core\Components\Common\CardGroup;
use Laka\Core\Components\Common\CardHeader;
use Laka\Core\Components\Common\CardText;
use Laka\Core\Components\Common\CardTitle;
use Laka\Core\Components\Common\Carousel;
use Laka\Core\Components\Common\Embed;
use Laka\Core\Components\Common\Headline;
use Laka\Core\Components\Common\Link;
use Laka\Core\Components\Common\Media;
use Laka\Core\Components\Common\Image;
use Laka\Core\Components\Common\Svg;
use Laka\Core\Components\Common\Toasts;
use Laka\Core\Components\Forms\Checkbox;
use Laka\Core\Components\Forms\Datepicker;
use Laka\Core\Components\Forms\Form;
use Laka\Core\Components\Forms\Group;
use Laka\Core\Components\Forms\Input;
use Laka\Core\Components\Forms\Label;
use Laka\Core\Components\Forms\Radio;
use Laka\Core\Components\Forms\Select;
use Laka\Core\Components\Forms\Textarea;
use Laka\Core\Components\Tables\Pagination;
use Laka\Core\Components\Tables\Table;
use Laka\Core\Components\Tables\TableColumn;
use Laka\Core\Components\Tables\TableFilter;
use Laka\Core\Components\Tables\TableRow;
use Laka\Core\Components\Tables\TableSort;

return [
    'prefix' => 'bootstrap',
    'pagination' => [
        'onEachPage' => 1, // Số trang hiển thị 2 bên trang hiện tại
        'numberFirstPage' => 1, // Số trang đầu tiên cân hiển thị,
        'numberLastPage' => 1, // Số trang cuối cân hiển thị
        'perPage' => 15
    ],
    'components' => [
        'datepicker' => [
            'view'  => 'components.forms.datepicker',
            'class' => Datepicker::class
        ],
        'form' => [
            'view'  => 'components.forms.form',
            'class' => Form::class
        ],
        'form-group' => [
            'view'  => 'components.forms.group',
            'class' => Group::class
        ],
        'form-input' => [
            'view'  => 'components.forms.input',
            'class' => Input::class
        ],
        'form-label' => [
            'view'  => 'components.forms.label',
            'class' => Label::class
        ],
        'form-select' => [
            'view'  => 'components.forms.select',
            'class' => Select::class
        ],
        'form-checkbox' => [
            'view'  => 'components.forms.checkbox',
            'class' => Checkbox::class
        ],
        'form-radio' => [
            'view'  => 'components.forms.radio',
            'class' => Radio::class
        ],
        'form-textarea' => [
            'view'  => 'components.forms.textarea',
            'class' => Textarea::class
        ],
        'table' => [
            'view'  => 'components.tables.table',
            'class' => Table::class
        ],
        'table-row' => [
            'view'  => 'components.tables.table-row',
            'class' => TableRow::class
        ],
        'table-column' => [
            'view'  => 'components.tables.table-column',
            'class' => TableColumn::class
        ],
        'table-sort' => [
            'view'  => 'components.tables.table-sort',
            'class' => TableSort::class
        ],
        'table-filter' => [
            'view'  => 'components.tables.table-filter',
            'class' => TableFilter::class
        ],
        'pagination' => [
            'view'  => 'components.tables.pagination',
            'class' => Pagination::class
        ],
        'alert' => [
            'view'  => 'components.common.alert',
            'class' => Alert::class
        ],
        'card' => [
            'view'  => 'components.common.card',
            'class' => Card::class
        ],
        'card-header' => [
            'view'  => 'components.common.card-header',
            'class' => CardHeader::class
        ],
        'card-title' => [
            'view'  => 'components.common.card-title',
            'class' => CardTitle::class
        ],
        'card-footer' => [
            'view'  => 'components.common.card-footer',
            'class' => CardFooter::class
        ],
        'card-text' => [
            'view'  => 'components.common.card-text',
            'class' => CardText::class
        ],
        'card-group' => [
            'view'  => 'components.common.card-group',
            'class' => CardGroup::class
        ],
        'badge' => [
            'view'  => 'components.common.badge',
            'class' => Badge::class
        ],
        'breadcrumb' => [
            'view'  => 'components.common.breadcrumb',
            'class' => Breadcrumb::class
        ],
        'carousel' => [
            'view'  => 'components.common.carousel',
            'class' => Carousel::class
        ],
        'embed' => [
            'view'  => 'components.common.embed',
            'class' => Embed::class
        ],
        'headline' => [
            'view'  => 'components.common.headline',
            'class' => Headline::class
        ],
        'link' => [
            'view'  => 'components.common.link',
            'class' => Link::class
        ],
        'media' => [
            'view'  => 'components.common.media',
            'class' => Media::class
        ],
        'image' => [
            'view'  => 'components.common.image',
            'class' => Image::class
        ],
        'svg' => [
            'view'  => 'components.common.svg',
            'class' => Svg::class
        ],
        'button' => [
            'view'  => 'components.common.button',
            'class' => Button::class
        ],
        'toasts' => [
            'view'  => 'components.common.toasts',
            'class' => Toasts::class
        ],
    ],
    'form-components' => [
        'bsInput' => [
            'view'      => Input::class,
            'params'    => ['name', 'type', 'class', 'groupClass', 'icon', 'prepent', 'size', 'value']
        ],
        'bsCheckbox' => [
            'view'      => Checkbox::class,
            'params'    => ['name', 'label', 'custom', 'checked']
        ],
        'bsRadio' => [
            'view'      => Radio::class,
            'params'    => ['name', 'label', 'custom', 'value', 'checked']
        ]
    ]
];
