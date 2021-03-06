<?php
namespace Vnnit\Core\Traits\Grids;

use Vnnit\Core\Facades\Common;
use Vnnit\Core\Grids\GenericButton;

trait RendersButtons
{
    protected $buttons = [];
    protected $exceptButtons = null;

    protected $buttonsToGenerate = [];

    private $actionsToButtonGenerate = [
        'create' => 'add',
        'edit' => 'edit',
        'detail' => 'view',
        'delete' => 'delete',
        'refresh' => 'view'
    ];

    private function getConfigPrefix()
    {
        return config('vnnit-core.prefix');
    }

    /**
     * Sets an array of buttons that would be rendered to the grid
     *
     * @return void
     * @throws \Exception
     */
    public function setButtons()
    {
        $this->setDefaultButtons();
    }

    /**
     * Set default buttons for the grid
     *
     * @return void
     */
    private function setDefaultButtons()
    {
        $this->buttons = [
            // toolbar buttons
            GenericButton::TYPE_TOOLBAR => [
                'create' => $this->getCreateButton(),
                'refresh' => $this->getRefreshButton()
            ],
            // row buttons
            GenericButton::TYPE_ROW => [
                'edit' => $this->getEditButton(),
                'detail' => $this->getDetailButton(),
                'delete' => $this->getDeleteButton()
            ]
        ];
    }

    public function getButton($key, $type = GenericButton::TYPE_TOOLBAR)
    {
        return data_get($this->buttons, $type.'.'.$key, null);
    }

    public function getButtons($type = GenericButton::TYPE_TOOLBAR)
    {
        $btns = data_get($this->buttons, $type, $this->buttons);

        return collect($btns)->sortBy('position')->toArray();
    }

    public function hasButton($key, $type = GenericButton::TYPE_TOOLBAR)
    {
        return !is_null($this->getButton($key, $type));
    }

    protected function configureCreateButton()
    {
        return [
            'key' => 'create',
            'name' => 'create',
            'title' => translate('table.btn_create'),
            'label' => translate('table.btn_create'),
            'variant' => 'success',
            'class' => 'show_modal_form',
            'size' => '',
            'position' => 1,
            'url' => function($item) {
                return $this->getCreareUrl();
            },
            'dataAttributes' => [
                'loading' => translate('table.loading_text')
            ],
            'icon' => 'fa-plus-circle',
            'visible' => function($item) {
                return $this->visibleCreate();
            }
        ];
    }

    protected function configureRefreshButton()
    {
        return [
            'key' => 'refresh',
            'name' => 'refresh',
            'title' => translate('table.btn_refresh'),
            'label' => translate('table.btn_refresh'),
            'variant' => 'primary',
            'size' => '',
            'position' => 2,
            'dataAttributes' => [
                'trigger-pjax' => 1,
                'pjax-target' => '#'.$this->getId(),
                'loading' => translate('table.loading_text')
            ],
            'url' => function($item) {
                return $this->getRefreshUrl();
            },
            'icon' => 'fa-refresh',
            'visible' => function($item) {
                return $this->visibleRefresh();
            }
        ];
    }

    protected function configureEditButton()
    {
        $prefix = $this->getConfigPrefix();
        return [
            'key' => 'edit',
            'name' => 'edit',
            'variant' => 'primary',
            'position' => 1,
            'icon' => 'fa fa-edit',
            'title' => translate('table.btn_edit'),
            'renderCustom' => "{$prefix}::tables.buttons.action_edit",
            'url' => function($item) {
                return $this->getEditUrl([data_get($item, 'id'), 'ref' => $this->getId()]);
            },
            'dataAttributes' => [
                'loading' => translate('table.loading_text')
            ],
            'type' => GenericButton::TYPE_ROW,
            'visible' => function($item) {
                return $this->visibleEdit($item);
            }
        ];
    }

    protected function configureDetailButton()
    {
        $prefix = $this->getConfigPrefix();
        return [
            'key' => 'show',
            'name' => 'show',
            'variant' => 'info',
            'position' => 2,
            'icon' => 'fa fa-info-circle',
            'title' => translate('table.btn_detail'),
            // 'renderCustom' => "{$prefix}::tables.buttons.action_show",
            'url' => function($item) {
                return $this->getDetailUrl(data_get($item, 'id'));
            },
            'dataAttributes' => [
                'loading' => translate('table.loading_text')
            ],
            'type' => GenericButton::TYPE_ROW,
            'visible' => function($item) {
                return $this->visibleDetail($item);
            }
        ];
    }

    protected function configureDeleteButton()
    {
        $prefix = $this->getConfigPrefix();
        return [
            'key' => 'destroy',
            'name' => 'destroy',
            'variant' => 'danger',
            'position' => 3,
            'icon' => 'fa fa-trash',
            'class' => 'data-remote',
            'title' => translate('table.btn_delete'),
            // 'renderCustom' => "{$prefix}::tables.buttons.action_destroy",
            'url' => function($item) {
                return $this->getDeleteUrl(data_get($item, 'id'));
            },
            'dataAttributes' => [
                'loading' => translate('table.loading_text'),
                'trigger-confirm' => 1,
                'confirmation-msg' => translate('table.action_question_delete'),
                'method' => 'DELETE',
                'pjax-target' => '#'.$this->getId()
            ],
            'type' => GenericButton::TYPE_ROW,
            'visible' => function($item) {
                return $this->visibleDelete($item);
            }
        ];
    }

    private function setDefaultButtonsToGenerate()
    {
        if (count($this->buttonsToGenerate) == 0) {
            if (is_null($this->exceptButtons)) {
                $this->exceptButtons = [];
                $sectionCode = Common::getSectionCode();
                foreach($this->actionsToButtonGenerate as $key => $action) {
                    if (!user_can("{$action}_{$sectionCode}")) {
                        array_push($this->exceptButtons, $key);
                    }
                }
            }
            $this->buttonsToGenerate = array_keys(array_except($this->actionsToButtonGenerate, $this->exceptButtons));
        }
    }

    protected function visibleCreate()
    {
        return in_array('create', $this->buttonsToGenerate);
    }

    protected function visibleRefresh()
    {
        return in_array('refresh', $this->buttonsToGenerate);
    }

    protected function visibleEdit($item)
    {
        return in_array('edit', $this->buttonsToGenerate);;
    }

    protected function visibleDetail($item)
    {
        return in_array('detail', $this->buttonsToGenerate);;
    }

    protected function visibleDelete($item)
    {
        return in_array('delete', $this->buttonsToGenerate);;
    }

    protected function getCreareUrl()
    {
        return route($this->getSectionCode().'.create', ['ref' => $this->getId()]);
    }
    protected function getRefreshUrl()
    {
        return route($this->getIndexRouteName());
    }
    protected function getEditUrl($params)
    {
        return $params ? route($this->getSectionCode().'.edit', $params) : '';
    }
    protected function getDetailUrl($params)
    {
        return $params ? route($this->getSectionCode().'.show', $params) : '';
    }
    protected function getDeleteUrl($params)
    {
        return $params ? route($this->getSectionCode().'.destroy', $params) : '';
    }

    private function getCreateButton()
    {
        return GenericButton::make($this->configureCreateButton());
    }

    private function getRefreshButton()
    {
        return GenericButton::make($this->configureRefreshButton());
    }
    private function getEditButton()
    {
        return GenericButton::make($this->configureEditButton());
    }
    private function getDetailButton()
    {
        return GenericButton::make($this->configureDetailButton());
    }
    private function getDeleteButton()
    {
        return GenericButton::make($this->configureDeleteButton());
    }
}
