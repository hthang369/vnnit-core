<?php
namespace Vnnit\Core\Forms\Fields;

use Illuminate\Support\Arr;
use Illuminate\View\ComponentAttributeBag;
use Vnnit\Core\Forms\Form;
use Vnnit\Core\Traits\Grids\CallableData;

abstract class FormField
{
    use CallableData;
    /**
     * Type of the field.
     *
     * @var string
     */
    protected $type;

    /**
     * Name of the field.
     *
     * @var string
     */
    protected $name;

    /**
     * @var Form
     */
    protected $parent;

    /**
     * All options for the field.
     *
     * @var array
     */
    protected $options = [];

    abstract protected function getTemplate();
    abstract protected function getAttributes();

    public function __construct($name, $type, Form $parent, array $options = [])
    {
        $this->type = $type;
        $this->name = $name;
        $this->parent = $parent;
        $this->setDefaultOptions($options);
    }

    public static function make($name, $type)
    {
        $obj = new static($name, $type);
        return $obj;
    }

    /**
     * Set single option on the field.
     *
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function setOption($name, $value)
    {
        Arr::set($this->options, $name, $value);

        return $this;
    }

    /**
     * Defaults used across all fields.
     *
     * @return array
     */
    private function allDefaults()
    {
        return [
            'wrapper' => ['class' => $this->parent->getConfig('defaults.wrapper_class')],
            'attr' => ['class' => $this->parent->getConfig('defaults.field_class')],
            'help_block' => ['text' => null, 'tag' => 'p', 'attr' => [
                'class' => $this->parent->getConfig('defaults.help_block_class')
            ]],
            'value' => null,
            'default_value' => null,
            'label' => null,
            'label_show' => true,
            'is_child' => false,
            'label_attr' => ['class' => $this->parent->getConfig('defaults.label_class')],
            'label_for' => '',
            'errors' => ['class' => $this->parent->getConfig('defaults.error_class')],
            'rules' => [],
            'error_messages' => []
        ];
    }

    /**
     * Default options for field.
     *
     * @return array
     */
    protected function getDefaults()
    {
        return [];
    }

    /**
     * Merge all defaults with field specific defaults and set template if passed.
     *
     * @param array $options
     */
    protected function setDefaultOptions(array $options = [])
    {
        $this->options = $this->parent->mergeOptions($this->allDefaults(), $this->getDefaults());

        $this->options = array_merge($this->options, $options);

        $this->options = array_merge($this->options, $this->getAttributes());

        $this->setOption('label_for', $this->name);

        $this->setupLabel();
    }

    /**
     * Setup the label for the form field.
     *
     * @return void
     */
    public function setupLabel()
    {
        if ($this->getOption('label') !== null) {
            return;
        }

        if ($langName = $this->parent->languageName) {
            $label = sprintf('%s.%s', $langName, $this->name);
        } else {
            $label = $this->name;
        }

        $this->setOption('label', $this->parent->formatLabel($label));
    }

    /**
     * Get field options.
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Get single option from options array. Can be used with dot notation ('attr.class').
     *
     * @param string $option
     * @param mixed|null $default
     * @return mixed
     */
    public function getOption($option, $default = null)
    {
        return Arr::get($this->options, $option, $default);
    }

    public function getView()
    {
        $prefix = config('vnnit-core.prefix');
        $view = 'components.form-field.'.$this->getTemplate();
        return "{$prefix}::{$view}";
    }

    protected function getCompactData()
    {
        $attrs = array_except(get_object_vars($this), ['parent']);
        return $attrs;
    }

    public function render()
    {
        return view($this->getView(), $this->getCompactData())->render();
    }
}
