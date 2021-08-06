<?php

/**
* @author Abdeslam Gacemi <abdobling@gmail.com>
*/

namespace Abdeslam\Validator\Rules;

use Abdeslam\Validator\Validator;

class StringRule extends Rule
{
    public function __invoke(Validator $v, string $field, array $data, array $params)
    {
        if (! is_string($data[$field])) {
            $v->addError($field, "The field '$field' must be a string");
            return false;
        }
    }
}