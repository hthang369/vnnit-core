<?php
namespace Vnnit\Core\Presenters;

use Collective\Html\HtmlFacade as Html;
use Nwidart\Menus\Presenters\Presenter;

abstract class BaseMenuPresenter extends Presenter
{
    protected $wrapperTag = 'ul';
    protected $wrapperAttrs = [
        'class' => 'nav'
    ];
    protected $dividerTag = 'li';
    protected $dividerAttrs = [
        'class' => 'divider'
    ];
    protected $headerTag = 'li';
    protected $headerAttrs = [
        'class' => 'dropdown-header'
    ];
    protected $elementTag = 'li';
    protected $elementAttrs = [
        'class' => 'nav-item'
    ];
    protected $dropdownTag = 'ul';
    protected $dropdownAttrs = [
        'class' => 'dropdown-item'
    ];

    /**
     * Get open tag wrapper.
     *
     * @return string
     */
    public function getOpenTagWrapper()
    {
        return sprintf('<%s%s>', $this->wrapperTag, Html::attributes($this->wrapperAttrs));
    }

    /**
     * Get close tag wrapper.
     *
     * @return string
     */
    public function getCloseTagWrapper()
    {
        return sprintf('</%s>', $this->wrapperTag);
    }

    /**
     * Get menu tag without dropdown wrapper.
     *
     * @param \Nwidart\Menus\MenuItem $item
     *
     * @return string
     */
    public function getMenuWithoutDropdownWrapper($item)
    {
        $attributes = preg_replace('/class="(\S+)"/', 'class="$1'.$this->getActiveState($item, ' active').'"', $item->getAttributes());
        $content = Html::link($item->getUrl(), $item->getIcon().' '.$this->getMenuTitle($item), [$attributes]);
        return $this->getElementTag($this->elementTag, $content, $this->elementAttrs);
    }

    /**
     * Get divider tag wrapper.
     *
     * @return string
     */
    public function getDividerWrapper()
    {
        return $this->getElementTag($this->dividerTag, '', $this->dividerAttrs);
    }

    /**
     * Get header dropdown tag wrapper.
     *
     * @param \Nwidart\Menus\MenuItem $item
     *
     * @return string
     */
    public function getHeaderWrapper($item)
    {
        return $this->getElementTag($this->headerTag, $this->getMenuTitle($item), $this->headerAttrs);
    }

    /**
     * Get menu tag with dropdown wrapper.
     *
     * @param \Nwidart\Menus\MenuItem $item
     *
     * @return string
     */
    public function getMenuWithDropDownWrapper($item)
    {
        $attributes = preg_replace('/class="(\S+)"/', 'class="$1'.$this->getActiveStateOnChild($item, ' active').'"', $item->getAttributes());
        $title = $item->getIcon().' '.$this->getMenuTitle($item).' <b class="caret"></b>';
        $content = Html::link($item->getUrl(), $title, [$attributes]);
        $content .= PHP_EOL.$this->getElementTag($this->dropdownTag, $this->getChildMenuItems($item), $this->dropdownAttrs);
        return $this->getElementTag($this->elementTag, $content, $this->elementAttrs);
    }

    /**
     * Get multi level dropdown menu wrapper.
     *
     * @param \Nwidart\Menus\MenuItem $item
     *
     * @return string
     */
    public function getMultiLevelDropdownWrapper($item)
    {
        $attributes = preg_replace('/class="(\S+)"/', 'class="$1'.$this->getActiveStateOnChild($item, ' active').'"', $item->getAttributes());
        $title = $item->getIcon().' '.$this->getMenuTitle($item).' <b class="caret"></b>';
        $content = Html::link($item->getUrl(), $title, [$attributes]);
        $content .= PHP_EOL.$this->getElementTag($this->dropdownTag, $this->getChildMenuItems($item), $this->dropdownAttrs);
        return $this->getElementTag($this->elementTag, $content, $this->elementAttrs);
    }

    protected function getElementTag($tag, $content, $attrs = [])
    {
        return Html::tag($tag, $content, $attrs)->toHtml();
    }

    /**
     * Get title for menu.
     *
     * @param \Nwidart\Menus\MenuItem $item
     *
     * @return string
     */
    protected function getMenuTitle($item)
    {
        return $item->title;
    }

    /**
     * Get active state on child items.
     *
     * @param $item
     * @param string $state
     *
     * @return null|string
     */
    protected function getActiveStateOnChild($item, $state = 'active')
    {
        return $item->hasActiveOnChild() ? $state : null;
    }

    /**
     * {@inheritdoc }.
     */
    public function getActiveState($item, $state = 'active')
    {
        return $item->isActive() ? $state : null;
    }
}
