<?php

/**
* @author Abdeslam Gacemi <abdobling@gmail.com>
*/

namespace Abdeslam\Validator\Rules;

use Abdeslam\Validator\Validator;

class JsonRule extends Rule
{
    public function __invoke(Validator $v, string $field, array $data, array $params)
    {
        json_decode($data[$field]);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $v->addError($field, "The field '$field' must contain valid json content");
            return false;
        }
    }
}