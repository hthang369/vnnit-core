<?php
namespace Vnnit\Core\Grids;

use Illuminate\Contracts\Support\Htmlable;
use InvalidArgumentException;

class GenericButton implements Htmlable
{
    const TYPE_TOOLBAR = 'toolbar';
    const TYPE_ROW = 'row';

    /**
     * @var string
     */
    protected $key = null;
    /**
     * @var string
     */
    protected $label = null;
    /**
     * The title of the button
     *
     * @var string
     */
    protected $title = '';
    /**
     * @var integer
     */
    protected $position = null;

    /**
     * Specify a custom way to render the button
     *
     * @var callable
     */
    protected $renderCustom = null;

    /**
     * The link of the button
     *
     * @var string|callable
     */
    protected $url = '#';

    /**
     * The buttons name
     *
     * @var string
     */
    protected $name = 'Unknown';

    /**
     * The buttons ability to support pjax
     *
     * @var bool
     */
    protected $pjaxEnabled = false;

    protected $variant = 'info';

    /**
     * The classes for the button
     *
     * @var string
     */
    protected $class = '';

    /**
     * The icon to be displayed, if any
     *
     * @var null
     */
    protected $icon = null;

    /**
     * The icon to be displayed, if any
     *
     * @var null
     */
    protected $size = 'sm';

    /**
     * If a modal should be displayed
     *
     * @var bool
     */
    protected $showModal = false;

    /**
     * Any available data attributes
     *
     * @var array
     */
    protected $dataAttributes = [];

    /**
     * The id of the grid in question. Will be used for PJAX
     *
     * @var string
     */
    protected $gridId;

    /**
     * Type of button. Can be one of either `rows` or `toolbar`
     * @var string
     */
    protected $type = 'toolbar';

    /**
     * CreateButton constructor.
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        foreach ($params as $k => $v) {
            $this->__set($k, $v);
        }
        $className = $this->class;
        $this->class = array_css_class(array_filter([
            'btn',
            "btn-{$this->size}" => !empty($this->size),
            "btn-{$this->variant}",
            $className
        ]));
    }

    /**
     * Get a class attribute
     *
     * @param $name
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->{$name};
        }
        throw new InvalidArgumentException("The property " . $name . " does not exist on " . get_called_class());
    }

    /**
     * Set a class attribute
     *
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->{$name} = $value;
    }

    public static function make($attributes)
    {
        $object = new self($attributes);
        return $object;
    }

    /**
     * @return string
     * @throws \Throwable
     */
    public function __toString()
    {
        return $this->toHtml();
    }

    /**
     * Get content as a string of HTML.
     *
     * @return string
     * @throws \Throwable
     */
    public function toHtml()
    {
        return $this->render();
    }

    /**
     * Render the button
     *
     * @param array $args
     * @return string
     * @throws \Throwable
     */
    public function render(...$args)
    {
        // apply preset attributes
        $this->dataAttributes = $this->getDataAttributes();

        // check if modal is needed, and adjust the class attribute
        $this->showModal ? $this->__set('class', $this->class.' show_modal_form') : false;

        // can render
        if (!is_callable($this->visible)) {
            $this->visible = function () {
                return true;
            };
        }
        // custom render
        if ($this->renderCustom && is_callable($this->renderCustom)) {
            return call_user_func($this->renderCustom, $this->compactData($args));
        }

        // collapse the array of args into a single 1d array, so that the values passed can be
        // accessed as key value pair
        $args = array_collapse($args);

        return view($this->getButtonView(), $this->compactData($args))->render();
    }

    /**
     * @return array
     */
    public function getDataAttributes(): array
    {
        if ($this->pjaxEnabled) {
            // set by default some attributes to control PJAX on the front end
            return array_merge($this->dataAttributes, [
                'trigger-pjax' => true,
                'pjax-target' => '#' . $this->gridId
            ]);
        }
        return $this->dataAttributes;
    }

    /**
     * Specify the data to be sent to the view
     *
     * @param array $params
     * @return array
     */
    protected function compactData($params)
    {
        foreach (array_merge($params, $this->getExtraParams()) as $key => $value) {
            $this->__set($key, $value);
        }
        return get_object_vars($this);
    }

    /**
     * Allow extra parameters to be added on this object
     *
     * @return array
     */
    public function getExtraParams()
    {
        return [];
    }

    /**
     * Return the view name used to render the button
     *
     * @return string
     */
    public function getButtonView(): string
    {
        $prefix = config('vnnit-core.prefix');
        return "{$prefix}::components.grids.buttons";
    }
}
