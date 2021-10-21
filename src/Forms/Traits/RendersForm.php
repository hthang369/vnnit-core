<?php
namespace Vnnit\Core\Forms\Traits;

use Illuminate\Support\Arr;

trait RendersForm
{
    /**
     * @param $formOptions
     * @return array
     */
    protected function buildFormOptionsForFormBuilder($formOptions)
    {
        $reserved = ['method', 'url', 'route', 'action', 'files'];
        $formAttributes = Arr::get($formOptions, 'attr', []);

        // move string value to `attr` to maintain backward compatibility
        foreach ($formOptions as $key => $formOption) {
            if (!in_array($formOption, $reserved) && is_string($formOption)) {
                $formAttributes[$key] = $formOption;
            }
        }

        return array_merge(
            $formAttributes, Arr::only($formOptions, $reserved)
        );
    }

    protected function getView()
    {
        $prefix = config('vnnit-core.prefix');
        $view = 'components.form-field.form';
        return "{$prefix}::{$view}";
    }

    /**
     * Render the form.
     *
     * @param array $options
     * @param string $fields
     * @param bool $showStart
     * @param bool $showFields
     * @param bool $showEnd
     * @return string
     */
    protected function render($options, $fields, $showStart, $showFields, $showEnd)
    {
        $this->formOptions = $this->buildFormOptionsForFormBuilder(
            $this->mergeOptions($this->formOptions, $options)
        );

        $this->setupNamedModel();

        $data = array_only(get_object_vars($this), ['formOptions', 'fields', 'model', 'exclude', 'form']);

        return view($this->getView(), array_merge($data, compact('showStart', 'showFields', 'showEnd')))->render();
    }

    /**
     * Render full form.
     *
     * @param array $options
     * @param bool  $showStart
     * @param bool  $showFields
     * @param bool  $showEnd
     * @return string
     */
    public function renderForm(array $options = [], $showStart = true, $showFields = true, $showEnd = true)
    {
        return $this->render($options, $this->fields, $showStart, $showFields, $showEnd);
    }

    /**
     * Render rest of the form.
     *
     * @param bool $showFormEnd
     * @param bool $showFields
     * @return string
     */
    public function renderRest($showFormEnd = true, $showFields = true)
    {
        $fields = $this->getUnrenderedFields();

        return $this->render([], $fields, false, $showFields, $showFormEnd);
    }

    /**
     * Renders the rest of the form up until the specified field name.
     *
     * @param string $field_name
     * @param bool   $showFormEnd
     * @param bool   $showFields
     * @return string
     */
    public function renderUntil($field_name, $showFormEnd = true, $showFields = true)
    {
        if (!$this->has($field_name)) {
            $this->fieldDoesNotExist($field_name);
        }

        $fields = $this->getUnrenderedFields();

        $i = 1;
        foreach ($fields as $key => $value) {
            if ($value->getRealName() == $field_name) {
                break;
            }
            $i++;
        }

        $fields = array_slice($fields, 0, $i, true);

        return $this->render([], $fields, false, $showFields, $showFormEnd);
    }
}
