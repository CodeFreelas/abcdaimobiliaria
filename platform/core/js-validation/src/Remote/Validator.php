<?php

namespace Srapid\JsValidation\Remote;

use Srapid\JsValidation\Support\AccessProtectedTrait;
use Srapid\JsValidation\Support\RuleListTrait;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\ValidationRuleParser;
use Illuminate\Validation\Validator as BaseValidator;

class Validator
{
    use AccessProtectedTrait;
    use RuleListTrait;

    /**
     * Validator extension name.
     */
    const EXTENSION_NAME = 'js_validation';

    /**
     * @var BaseValidator
     */
    protected $validator;

    /**
     * Whether to escape validation messages.
     *
     * @var bool
     */
    protected $escape;

    /**
     * RemoteValidator constructor.
     *
     * @param BaseValidator $validator
     * @param bool $escape
     */
    public function __construct(BaseValidator $validator, $escape = false)
    {
        $this->validator = $validator;
        $this->escape = $escape;
    }

    /**
     * Validate request.
     *
     * @param $field
     * @param $parameters
     * @return void
     *
     * @throws ValidationException
     */
    public function validate($field, $parameters = [])
    {
        $attribute = $this->parseAttributeName($field);
        $validationParams = $this->parseParameters($parameters);
        $validationResult = $this->validateJsRemoteRequest($attribute, $validationParams);

        $this->throwValidationException($validationResult, $this->validator);
    }

    /**
     *  Parse Validation input request data.
     *
     * @param string $data
     * @return array|int|string
     */
    protected function parseAttributeName($data)
    {
        parse_str($data, $attrParts);
        $attrParts = is_null($attrParts) ? [] : $attrParts;
        $newAttr = array_keys(Arr::dot($attrParts));

        return array_pop($newAttr);
    }

    /**
     *  Parse Validation parameters.
     *
     * @param array $parameters
     * @return array
     */
    protected function parseParameters($parameters)
    {
        $newParams = ['validate_all' => false];
        if (isset($parameters[0])) {
            $newParams['validate_all'] = ($parameters[0] === 'true') ? true : false;
        }

        return $newParams;
    }

    /**
     * Validate remote Javascript Validations.
     *
     * @param string $attribute
     * @param array $parameters
     * @return array|bool
     */
    protected function validateJsRemoteRequest($attribute, $parameters)
    {
        $this->setRemoteValidation($attribute, $parameters['validate_all']);

        $validator = $this->validator;
        if ($validator->passes()) {
            return true;
        }

        $messages = $validator->messages()->get($attribute);

        if ($this->escape) {
            foreach ($messages as $key => $value) {
                $messages[$key] = e($value);
            }
        }

        return $messages;
    }

    /**
     * Sets data for validate remote rules.
     *
     * @param string $attribute
     * @param bool $validateAll
     * @return void
     */
    protected function setRemoteValidation($attribute, $validateAll = false)
    {
        $validator = $this->validator;
        $rules = $validator->getRules();
        $rules = isset($rules[$attribute]) ? $rules[$attribute] : [];

        if (in_array('no_js_validation', $rules)) {
            $validator->setRules([$attribute => []]);

            return;
        }

        if (!$validateAll) {
            $rules = $this->purgeNonRemoteRules($rules, $validator);
        }

        $validator->setRules([$attribute => $rules]);
    }

    /**
     * Remove rules that should not be validated remotely.
     *
     * @param array $rules
     * @param BaseValidator $validator
     * @return mixed
     */
    protected function purgeNonRemoteRules($rules, $validator)
    {
        $this->createProtectedCaller($validator);

        foreach ($rules as $i => $rule) {
            $parsedRule = ValidationRuleParser::parse([$rule]);
            if (!$this->isRemoteRule($parsedRule[0])) {
                unset($rules[$i]);
            }
        }

        return $rules;
    }

    /**
     * Throw the failed validation exception.
     *
     * @param bool $result
     * @param BaseValidator $validator
     * @return void
     *
     * @throws ValidationException|HttpResponseException
     */
    protected function throwValidationException($result, $validator)
    {
        $response = new JsonResponse($result, 200);

        if ($result !== true && class_exists(ValidationException::class)) {
            throw new ValidationException($validator, $response);
        }

        throw new HttpResponseException($response);
    }
}
