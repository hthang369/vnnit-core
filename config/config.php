<?php

/*
 * You can place your custom package configuration in here.
 */

use Vnnit\Core\Components\Common\Alert;
use Vnnit\Core\Components\Common\Badge;
use Vnnit\Core\Components\Common\Breadcrumb;
use Vnnit\Core\Components\Common\Button;
use Vnnit\Core\Components\Common\Card;
use Vnnit\Core\Components\Common\CardFooter;
use Vnnit\Core\Components\Common\CardGroup;
use Vnnit\Core\Components\Common\CardHeader;
use Vnnit\Core\Components\Common\CardText;
use Vnnit\Core\Components\Common\CardTitle;
use Vnnit\Core\Components\Common\Carousel;
use Vnnit\Core\Components\Common\Col;
use Vnnit\Core\Components\Common\Embed;
use Vnnit\Core\Components\Common\Headline;
use Vnnit\Core\Components\Common\Link;
use Vnnit\Core\Components\Common\Media;
use Vnnit\Core\Components\Common\Image;
use Vnnit\Core\Components\Common\Portfolio;
use Vnnit\Core\Components\Common\Row;
use Vnnit\Core\Components\Common\Svg;
use Vnnit\Core\Components\Common\SectionBox;
use Vnnit\Core\Components\Common\SectionTitle;
use Vnnit\Core\Components\Common\Toasts;
use Vnnit\Core\Components\Forms\Checkbox;
use Vnnit\Core\Components\Forms\Datepicker;
use Vnnit\Core\Components\Forms\Form;
use Vnnit\Core\Components\Forms\Group;
use Vnnit\Core\Components\Forms\Input;
use Vnnit\Core\Components\Forms\Label;
use Vnnit\Core\Components\Forms\Radio;
use Vnnit\Core\Components\Forms\Select;
use Vnnit\Core\Components\Forms\Textarea;
use Vnnit\Core\Components\Tables\Pagination;
use Vnnit\Core\Components\Tables\Table;
use Vnnit\Core\Components\Tables\TableColumn;
use Vnnit\Core\Components\Tables\TableFilter;
use Vnnit\Core\Components\Tables\TableRow;
use Vnnit\Core\Components\Tables\TableSort;

return [
    'prefix' => 'bootstrap',
    'pagination' => [
        'onEachPage' => 1, // Số trang hiển thị 2 bên trang hiện tại
        'numberFirstPage' => 1, // Số trang đầu tiên cân hiển thị,
        'numberLastPage' => 1, // Số trang cuối cân hiển thị
        'perPage' => 15
    ],
    'search' => [
        'param' => 'q'
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
        'row' => [
            'view'  => 'components.common.row',
            'class' => Row::class
        ],
        'col' => [
            'view'  => 'components.common.col',
            'class' => Col::class
        ],
        'section-box' => [
            'view'  => 'components.common.section-box',
            'class' => SectionBox::class
        ],
        'section-title' => [
            'view'  => 'components.common.section-title',
            'class' => SectionTitle::class
        ],
        'portfolio' => [
            'view'  => 'components.common.portfolio',
            'class' => Portfolio::class
        ]
    ],
    'bt-components' => [
        'btText' => [
            'view'      => 'components.bootstrap.forms.input',
            'params'    => ['name', 'value', 'options' => [], 'type' => 'text']
        ],
        'btButton' => [
            'view'      => 'components.bootstrap.forms.button',
            'params'    => ['text', 'variant' => '', 'options' => [], 'type' => 'button', 'btnType' => 'button']
        ],
        'btSubmit' => [
            'view'      => 'components.bootstrap.forms.button',
            'params'    => ['text', 'variant' => '', 'options' => [], 'type' => 'submit', 'btnType' => 'button']
        ],
        'btSelect' => [
            'view'      => 'components.bootstrap.forms.select',
            'params'    => ['name', 'list', 'selected', 'attributes' => [], 'options' => []]
        ]
    ]
];
