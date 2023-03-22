<?php

namespace Kronas\Lib\Validator;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Kronas\Api\BaseApiException;

class Validator
{
    private array $messages = [];

    /**
     * Ініціалізація
     *
     * @param array $rules - [field => [rule]]
     * @param array $errors - [rule => message]
     */
    public function __construct(
        private array $rules,
        private array $errors
    ) {}

    /**
     * Ініціалізація
     *
     * @return $this
     */
    public function init(): Validator
    {
        foreach ($this->rules as $field => $rules) {
            foreach ($rules as $rule) {
                @list($rule, $value) = explode(':', $rule);

                if ($rule == 'nullable') {
                    continue;
                }
                if (empty($this->errors[$rule])) {
                    continue;
                }

                $text = $this->errors[$rule];

                if (is_array($text) && !empty($text)) {
                    $arr = array_filter($this->rules[$field], fn($rule) => in_array($rule, ['array', 'file', 'numeric', 'integer', 'string']));

                    if (empty($arr)) {
                        continue;
                    }

                    $text = $text[array_pop($arr)];
                }
                if ($value) {
                    $text = str($text)->replaceFirst(':value', $value)->toString();
                }

                $this->messages["{$field}.{$rule}"] = $text;
            }
        }

        return $this;
    }

    /**
     * Валідувати поля
     *
     * @param Request $request
     * @param array $fields
     * @return $this
     * @throws BaseApiException
     */
    public function validate(Request $request, array $fields): Validator
    {
        $rules = array_reduce($fields, function($rules, $field) {
            if (!key_exists($field, $this->rules)) {
                throw new BaseApiException("The {$field} field must be in rules");
            }

            $rules[$field] = $this->rules[$field];

            return $rules;
        }, []);

        try {
            \Illuminate\Support\Facades\Validator::validate($request->all(), $rules, $this->messages);
        } catch (ValidationException $e) {
            throw new BaseApiException(
                $e->errors()[array_key_first($e->errors())][0],
                $e->getCode(),
                $e->status
            );
        }

        return $this;
    }
}