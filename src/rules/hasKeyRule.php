<?php

/**
* @author Abdeslam Gacemi <abdobling@gmail.com>
*/

namespace Abdeslam\Validator\Rules;

use Abdeslam\Validator\Validator;

class HasKeyRule extends Rule
{
    public function __invoke(Validator $v, string $field, array $data, array $params)
    {
        if (! is_array($data[$field]) || ! array_key_exists($params[0], $data[$field])) {
            $v->addError($field, "The field '$field' must be an array containing the key {$params[0]}");
            return false;
        }
    }
}