<?php
namespace Vnnit\Core\Forms;

use Illuminate\Support\Arr;
use Vnnit\Core\Forms\Fields\FormField;
use Vnnit\Core\Forms\Traits\FormHelper;
use Vnnit\Core\Forms\Traits\RendersForm;
use Vnnit\Core\Traits\Grids\CallableData;

abstract class Form
{
    use FormHelper, CallableData, RendersForm;
    /**
     * All fields that are added.
     *
     * @var array
     */
    protected $fields = [];

    /**
     * Additional data which can be used to build fields.
     *
     * @var array
     */
    protected $data = [];

    /**
     * Model to use.
     *
     * @var mixed
     */
    protected $model = [];

    /**
     * Form options.
     *
     * @var array
     */
    protected $formOptions = [
        'method' => 'GET',
        'url' => null,
        'attr' => [],
    ];

    /**
     * Name of the parent form if any.
     *
     * @var string|null
     */
    protected $name = null;

    /**
     * @var FormBuilder
     */
    protected $formBuilder;

    /**
     * List of fields to not render.
     *
     * @var array
     **/
    protected $exclude = [];

    /**
     * @var string
     */
    protected $languageName;

    public function __construct()
    {
        $this->loadCustomTypes();
    }

    public function buildForm()
    {
    }

    public function add($name, $type = Field::TEXT, $options = [])
    {
        if ($this->has($name))
            return $this;

        $this->addField($this->makeField($name, $type, $options));
        return $this;
    }

    protected function makeField($name, $type, array $options = [])
    {
        $fieldName = $this->getFieldName($name);

        $fieldType = $this->getFieldType($type);

        return new $fieldType($fieldName, $type, $this, $options);
    }

    public function addField(FormField $field)
    {
        if ($field->type == 'file') {
            $this->formOptions['files'] = true;
        }

        $this->fields[$field->name] = $field;
    }

    /**
     * Check if form has field.
     *
     * @param string $name
     * @return bool
     */
    public function has($name)
    {
        return array_key_exists($name, $this->fields);
    }

    /**
     * Add any aditional data that field needs (ex. array of choices).
     *
     * @param string $name
     * @param mixed $data
     */
    public function setData($name, $data)
    {
        $this->data[$name] = $data;
    }

    /**
     * Get single additional data.
     *
     * @param string $name
     * @param null   $default
     * @return mixed
     */
    public function getData($name = null, $default = null)
    {
        if (is_null($name)) {
            return $this->data;
        }

        return Arr::get($this->data, $name, $default);
    }

    /**
     * Add multiple peices of data at once.
     *
     * @deprecated deprecated since 1.6.12, will be removed in 1.7 - use 3rd param on create, or 2nd on plain method to pass data
     * will be switched to protected in 1.7.
     * @param $data
     * @return $this
     **/
    public function addData(array $data)
    {
        foreach ($data as $key => $value) {
            $this->setData($key, $value);
        }

        return $this;
    }

    public function setFormBuilder(FormBuilder $builder)
    {
        $this->formBuilder = $builder;
        return $this;
    }

    public function setFormOptions(array $options)
    {
        $this->formOptions = $this->mergeOptions($this->formOptions, $options);
        $this->pullFromOptions('name', 'setName');
        $this->pullFromOptions('model', 'setupModel');
        $this->pullFromOptions('language_name', 'setLanguageName');
        return $this;
    }

    protected function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Setup model for form, add namespace if needed for child forms.
     *
     * @return $this
     */
    protected function setupModel($model)
    {
        $this->model = $model;
        $this->setupNamedModel();

        return $this;
    }

    /**
     * Set namespace to model if form is named so the data is bound properly.
     * Returns true if model is changed, otherwise false.
     *
     * @return bool
     */
    protected function setupNamedModel()
    {
        if (!$this->model || !$this->name) {
            return false;
        }

        $dotName = $this->getNameKey();
        $model = $this->convertModelToArray($this->model);
        $isCollectionFormModel = (bool) preg_match('/^.*\.\d+$/', $dotName);
        $isCollectionPrototype = strpos($dotName, '__NAME__') !== false;

        if (!Arr::get($model, $dotName) && !$isCollectionFormModel && !$isCollectionPrototype) {
            $newModel = [];
            Arr::set($newModel, $dotName, $model);
            $this->model = $newModel;

            return true;
        }

        return false;
    }

    /**
     * Set a language name, used as prefix for translated strings.
     *
     * @param string $prefix
     * @return $this
     */
    public function setLanguageName($prefix)
    {
        $this->languageName = (string) $prefix;

        return $this;
    }

    /**
     * Get dot notation key for the form.
     *
     * @return string
     **/
    public function getNameKey()
    {
        return $this->transformToDotSyntax($this->name);
    }

    /**
     * If form is named form, modify names to be contained in single key (parent[child_field_name]).
     *
     * @param string $name
     * @return string
     */
    protected function getFieldName($name)
    {
        $formName = $this->name;
        if ($formName !== null) {
            if (strpos($formName, '[') !== false || strpos($formName, ']') !== false) {
                return $this->transformToBracketSyntax(
                    $this->transformToDotSyntax(
                        $formName . '[' . $name . ']'
                    )
                );
            }

            return $formName . '[' . $name . ']';
        }

        return $name;
    }

    /**
     * Get model that is bind to form object.
     *
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }
}
