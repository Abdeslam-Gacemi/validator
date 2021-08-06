<?php

/**
* @author Abdeslam Gacemi <abdobling@gmail.com>
*/

namespace Abdeslam\Validator\Rules;

use Abdeslam\Validator\Validator;

class HasKeysRule extends Rule
{
    public function __invoke(Validator $v, string $field, array $data, array $params)
    {
        if (! is_array($data[$field]) || array_intersect($params, array_keys($data[$field])) !== $params) {
            $keys = implode(', ', $params);
            $v->addError($field, "The field '$field' must be an array containing the keys '$keys'");
            return false;
        }
    }
}