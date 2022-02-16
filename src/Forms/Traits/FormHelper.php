<?php
namespace Vnnit\Core\Forms\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Vnnit\Core\Forms\Field;
use Vnnit\Core\Forms\Fields\ButtonGroupType;
use Vnnit\Core\Forms\Fields\ButtonType;
use Vnnit\Core\Forms\Fields\CheckableGroupType;
use Vnnit\Core\Forms\Fields\CheckableType;
use Vnnit\Core\Forms\Fields\ChoiceType;
use Vnnit\Core\Forms\Fields\CollectionType;
use Vnnit\Core\Forms\Fields\EntityType;
use Vnnit\Core\Forms\Fields\InputType;
use Vnnit\Core\Forms\Fields\MapType;
use Vnnit\Core\Forms\Fields\PictureType;
use Vnnit\Core\Forms\Fields\RepeatedType;
use Vnnit\Core\Forms\Fields\SelectType;
use Vnnit\Core\Forms\Fields\StaticType;
use Vnnit\Core\Forms\Fields\TextareaType;

trait FormHelper
{
    protected $availableFieldTypes = [
        Field::TEXT             => InputType::class,
        Field::PASSWORD         => InputType::class,
        Field::EMAIL            => InputType::class,
        Field::NUMBER           => InputType::class,
        Field::URL              => InputType::class,
        Field::TEL              => InputType::class,
        Field::SEARCH           => InputType::class,
        Field::HIDDEN           => InputType::class,
        Field::DATE             => InputType::class,
        Field::FILE             => InputType::class,
        Field::MULTI_FILE       => InputType::class,
        Field::IMAGE            => InputType::class,
        Field::COLOR            => InputType::class,
        Field::DATETIME_LOCAL   => InputType::class,
        Field::MONTH            => InputType::class,
        Field::RANGE            => InputType::class,
        Field::TIME             => InputType::class,
        Field::WEEK             => InputType::class,
        Field::TEXTAREA         => TextareaType::class,
        Field::SELECT           => SelectType::class,
        Field::MULTI_SELECT     => SelectType::class,
        Field::BUTTON_BUTTON    => ButtonType::class,
        Field::BUTTON_SUBMIT    => ButtonType::class,
        Field::BUTTON_RESET     => ButtonType::class,
        Field::RADIO            => CheckableType::class,
        Field::CHECKBOX         => CheckableType::class,
        Field::STATIC           => StaticType::class,
        Field::BUTTON_GROUP     => ButtonGroupType::class,
        Field::CHOICE           => ChoiceType::class,
        Field::REPEATED         => RepeatedType::class,
        Field::ENTITY           => EntityType::class,
        Field::COLLECTION       => CollectionType::class,
        Field::CHECKBOX_GROUP   => CheckableGroupType::class,
        Field::RADIO_GROUP      => CheckableGroupType::class,
        Field::PICTURE          => PictureType::class,
        Field::MAP              => MapType::class,
        // 'form'           => 'ChildFormType',
    ];

    /**
     * Custom types
     *
     * @var array
     */
    private $customTypes = [];

    /**
     * @param string $key
     * @param string $default
     * @return mixed
     */
    public function getConfig($key = null, $default = null)
    {
        return config("form-builder.{$key}", $default);
    }

    /**
     * Get proper class for field type.
     *
     * @param $type
     * @return string
     */
    public function getFieldType($type)
    {
        if (!$type || trim($type) == '') {
            throw new \InvalidArgumentException('Field type must be provided.');
        }

        if ($this->hasCustomField($type)) {
            return $this->customTypes[$type];
        }

        $types = array_keys($this->availableFieldTypes);
        if (!in_array($type, $types)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Unsupported field type [%s]. Available types are: %s',
                    $type,
                    join(', ', array_merge($types, array_keys($this->customTypes)))
                )
            );
        }

        return data_get($this->availableFieldTypes, $type);
    }

    /**
     * Add custom field.
     *
     * @param $name
     * @param $class
     */
    public function addCustomField($name, $class)
    {
        if (!$this->hasCustomField($name)) {
            return $this->customTypes[$name] = $class;
        }

        throw new \InvalidArgumentException('Custom field ['.$name.'] already exists on this form object.');
    }

    /**
     * Merge options array.
     *
     * @param array $targetOptions
     * @param array $sourceOptions
     * @return array
     */
    public function mergeOptions(array $targetOptions, array $sourceOptions)
    {
        return array_replace_recursive($targetOptions, $sourceOptions);
    }

    /**
     * Load custom field types from config file.
     */
    private function loadCustomTypes()
    {
        $customFields = (array) $this->getConfig('custom_fields');

        if (!empty($customFields)) {
            foreach ($customFields as $fieldName => $fieldClass) {
                $this->addCustomField($fieldName, $fieldClass);
            }
        }
    }

    /**
     * Check if custom field with provided name exists
     * @param string $name
     * @return boolean
     */
    public function hasCustomField($name)
    {
        return array_key_exists($name, $this->customTypes);
    }

    /**
     * @param string $string
     * @return string
     */
    public function transformToBracketSyntax($string)
    {
        $name = explode('.', $string);
        if ($name && count($name) == 1) {
            return $name[0];
        }

        $first = array_shift($name);
        return $first . '[' . implode('][', $name) . ']';
    }

    /**
     * @param string $string
     * @return string
     */
    public function transformToDotSyntax($string)
    {
        return str_replace(['.', '[]', '[', ']'], ['_', '', '.', ''], $string);
    }

    /**
     * Format the label to the proper format.
     *
     * @param $name
     * @return string
     */
    public function formatLabel($name)
    {
        if (!$name) {
            return null;
        }

        if (trans()->has($name)) {
            $translatedName = trans($name);

            if (is_string($translatedName)) {
                return $translatedName;
            }
        }

        return ucfirst(str_replace('_', ' ', $name));
    }

    /**
     * Get single form option.
     *
     * @param string $option
     * @param mixed|null $default
     * @return mixed
     */
    public function getFormOption($option, $default = null)
    {
        return Arr::get($this->formOptions, $option, $default);
    }

    /**
     * Get an option from provided options and call method with that value.
     *
     * @param string $name
     * @param string $method
     */
    protected function pullFromOptions($name, $method)
    {
        if ($this->getFormOption($name) !== null) {
            $this->{$method}(Arr::pull($this->formOptions, $name));
        }
    }

    /**
     * @param object $model
     * @return object|null
     */
    public function convertModelToArray($model)
    {
        if (!$model) {
            return null;
        }

        if ($model instanceof Model) {
            return $model->toArray();
        }

        if ($model instanceof Collection) {
            return $model->all();
        }

        return $model;
    }
}
