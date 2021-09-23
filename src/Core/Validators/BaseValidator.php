<?php

namespace Vnnit\Core\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

/**
 * Class BaseValidator.
 *
 * @package namespace Vnnit\Core\Validators;
 */
class BaseValidator extends LaravelValidator
{
    /**
     * Validation Rules
     *
     * @var array
     */
    const ERROR_SQL_DUPLICATE_KEY = 1062;

    protected $rules = [
        ValidatorInterface::RULE_CREATE => [],
        ValidatorInterface::RULE_UPDATE => [],
    ];

    /**
     * Pass the data and the rules to the validator
     *
     * @param string $action
     * @return bool
     */
    public function passes($action = null)
    {
        $this->customRules($action);
        $this->customMessages($action);
        $this->customAttributes($action);
        return parent::passes($action);
    }

    /**
     * Custom rules
     *
     */
    protected function customRules($action = null)
    {
    }

    /**
     * Custom Messages
     *
     */
    protected function customMessages($action = null)
    {
    }

    /**
     * Custom Attributes
     *
     */
    protected function customAttributes($action = null)
    {
    }
}
